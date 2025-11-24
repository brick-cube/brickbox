<?php

namespace App\Filament\Company\Resources\Projects\Tables;

use App\Models\Employee;
use App\Models\SiteTransaction;
use App\Models\Attendance;
use App\Models\Project;

use Spatie\SimpleExcel\SimpleExcelWriter;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Response;
use Carbon\Carbon;

use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Columns\BadgeColumn;
use Filament\Tables\Table;

use Filament\Actions\EditAction;
use Filament\Actions\ViewAction;
use Filament\Actions\DeleteAction;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\Action;

use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;

use Illuminate\Support\Facades\Storage;

class ProjectsTable
{
    public static function configure(Table $table): Table
    {
        return $table
            ->columns([
                TextColumn::make('name')->label('Project Name')->searchable(),
                TextColumn::make('address')->label('Address')->limit(30)->searchable(),
                BadgeColumn::make('type')->label('Type'),
                TextColumn::make('area')->label('Area'),
                TextColumn::make('value')->label('Value'),
                TextColumn::make('created_at')->dateTime()->sortable(),
            ])

            ->filters([])

            ->recordActions([
                ViewAction::make(),
                EditAction::make(),
                DeleteAction::make(),

                // -------------------
                // Download project report (single-sheet stacked sections)
                // -------------------
                Action::make('download_report')
                    ->label('Download Report')
                    ->icon('heroicon-o-document-arrow-down')
                    ->action(function (Project $record) {

                        $start = $record->start_date ? Carbon::parse($record->start_date) : Carbon::minValue();
                        $end = $record->end_date ? Carbon::parse($record->end_date) : Carbon::now();

                        $transactions = $record->siteTransactions()
                            ->whereBetween('transaction_date', [$start, $end])
                            ->orderBy('transaction_date')
                            ->get();

                        $expenses = $transactions->where('transaction_type', 'expense');
                        $receipts = $transactions->where('transaction_type', 'receipt');

                        $attendances = $record->attendances()
                            ->whereBetween('date', [$start, $end])
                            ->where('status', 'present')
                            ->get()
                            ->groupBy('employee_id');

                        // Create spreadsheet
                        $spreadsheet = new Spreadsheet();
                        $sheet = $spreadsheet->getActiveSheet();
                        $sheet->setTitle('Project Report');

                        /* -------------------------------
         * MAIN HEADER
         * ----------------------------- */
                        $headerText = "{$record->name} Site Report from {$start->format('d M Y')} to {$end->format('d M Y')}";

                        $sheet->setCellValue("A1", $headerText);
                        $sheet->mergeCells("A1:G1");
                        $sheet->getStyle("A1")->getFont()->setBold(true)->setSize(18);
                        $sheet->getStyle("A1")->getAlignment()->setHorizontal('center');

                        $row = 3;

                        /* -------------------------------
         * Helper functions
         * ----------------------------- */
                        $sectionHeader = function ($title) use (&$row, $sheet) {
                            $sheet->setCellValue("A{$row}", strtoupper($title));
                            $sheet->mergeCells("A{$row}:G{$row}");
                            $sheet->getStyle("A{$row}")->getFont()->setBold(true)->setSize(14)->getColor()->setARGB("FFFFFFFF");
                            $sheet->getStyle("A{$row}:G{$row}")->getAlignment()->setHorizontal('center');
                            $sheet->getStyle("A{$row}:G{$row}")->getFill()->setFillType(
                                \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID
                            )->getStartColor()->setARGB("4472C4");
                            $row += 2;
                        };

                        $tableHeader = function ($headers) use (&$row, $sheet) {
                            $col = 'A';
                            foreach ($headers as $header) {
                                $sheet->setCellValue("{$col}{$row}", $header);
                                $sheet->getStyle("{$col}{$row}")->getFont()->setBold(true);
                                $sheet->getStyle("{$col}{$row}")->getBorders()->getAllBorders()
                                    ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);
                                $col++;
                            }
                            $row++;
                        };

                        foreach (range('A', 'G') as $column) {
                            $sheet->getColumnDimension($column)->setAutoSize(true);
                        }

                        /* -------------------------------
         * EXPENSES SECTION
         * ----------------------------- */
                        $sectionHeader("Expenses");

                        $tableHeader(['Date', 'Details', 'Description', 'Type', 'Amount']);

                        $totalExpense = 0;

                        foreach ($expenses as $exp) {
                            $sheet->fromArray([
                                $exp->transaction_date,
                                $exp->details,
                                $exp->description,
                                strtoupper($exp->transaction_type),
                                $exp->expense,
                            ], null, "A{$row}");

                            $totalExpense += $exp->expense;
                            $row++;
                        }

                        // Total row
                        $sheet->setCellValue("D{$row}", "TOTAL EXPENSE");
                        $sheet->setCellValue("E{$row}", $totalExpense);
                        $sheet->getStyle("D{$row}:E{$row}")->getFont()->getColor()->setARGB("FFFF0000");
                        $sheet->getStyle("D{$row}:E{$row}")->getFont()->setBold(true);
                        $row += 3;

                        /* -------------------------------
         * LABOUR SECTION
         * ----------------------------- */
                        $sectionHeader("Labour");

                        $tableHeader(['Worker', 'Position', 'Rate', 'Full Days', 'Half Days', 'Days Worked', 'Total Wage']);

                        $totalDays = 0;
                        $totalLabour = 0;

                        foreach ($attendances as $empId => $records) {
                            $emp = Employee::find($empId);
                            if (!$emp) continue;

                            $fullDays = $records->where('work_type', 'full')->count();
                            $halfDays = $records->where('work_type', 'half')->count();

                            $daysWorked = $fullDays + ($halfDays * 0.5);
                            $wage = ($emp->full_day_rate * $fullDays) + ($emp->half_day_rate * $halfDays);

                            $sheet->fromArray([
                                $emp->name,
                                $emp->position,
                                $emp->full_day_rate,
                                $fullDays,
                                $halfDays,
                                $daysWorked,
                                $wage,
                            ], null, "A{$row}");

                            $totalDays += $daysWorked;
                            $totalLabour += $wage;
                            $row++;
                        }

                        $sheet->setCellValue("F{$row}", "TOTAL DAYS");
                        $sheet->setCellValue("G{$row}", $totalDays);
                        $sheet->getStyle("F{$row}:G{$row}")->getFont()->setBold(true)->getColor()->setARGB("FFFF0000");
                        $row++;

                        $sheet->setCellValue("F{$row}", "TOTAL LABOUR AMOUNT");
                        $sheet->setCellValue("G{$row}", $totalLabour);
                        $sheet->getStyle("F{$row}:G{$row}")->getFont()->setBold(true)->getColor()->setARGB("FFFF0000");
                        $row += 3;

                        /* -------------------------------
         * RECEIPTS SECTION
         * ----------------------------- */
                        $sectionHeader("Receipts");

                        $tableHeader(['Date', 'Details', 'Description', 'Type', 'Amount']);

                        $totalReceipt = 0;

                        foreach ($receipts as $rec) {
                            $sheet->fromArray([
                                $rec->transaction_date,
                                $rec->details,
                                $rec->description,
                                strtoupper($rec->transaction_type),
                                $rec->expense,
                            ], null, "A{$row}");

                            $totalReceipt += $rec->expense;
                            $row++;
                        }

                        $sheet->setCellValue("D{$row}", "TOTAL RECEIPT");
                        $sheet->setCellValue("E{$row}", $totalReceipt);
                        $sheet->getStyle("D{$row}:E{$row}")->getFont()->setBold(true)->getColor()->setARGB("FFFF0000");

                        /* -------------------------------
         * Add borders to entire data body
         * ----------------------------- */
                        $lastDataRow = $row;
                        $sheet->getStyle("A3:G{$lastDataRow}")
                            ->getBorders()->getAllBorders()
                            ->setBorderStyle(\PhpOffice\PhpSpreadsheet\Style\Border::BORDER_THIN);

                        /* -------------------------------
         * EXPORT FILE
         * ----------------------------- */
                        $fileName = Str::slug($record->name) . '_site_report.xlsx';
                        $filePath = storage_path("app/{$fileName}");

                        $writer = new Xlsx($spreadsheet);
                        $writer->save($filePath);

                        return Response::download($filePath, $fileName)->deleteFileAfterSend(true);
                    })


            ])

            ->toolbarActions([
                BulkActionGroup::make([
                    DeleteBulkAction::make(),
                ]),
            ]);
    }
}
