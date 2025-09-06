<?php

namespace App\Filament\Resources;

use App\Filament\Resources\TransaksiResource\Pages;
use App\Models\Produk;
use App\Models\Transaksi;
use Filament\Forms;
use Filament\Forms\Form;
use Filament\Resources\Resource;
use Filament\Tables;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class TransaksiResource extends Resource
{
    protected static ?string $model = Transaksi::class;

    protected static ?string $navigationIcon = 'heroicon-o-arrows-right-left';

    public static function form(Form $form): Form
    {
        return $form
            ->schema([
                Forms\Components\Select::make('produk_id')
                    ->relationship('produk', 'nama_produk')
                    ->searchable()
                    ->preload()
                    ->required(),
                Forms\Components\Select::make('tipe')
                    ->options([
                        'masuk' => 'Barang Masuk',
                        'keluar' => 'Barang Keluar',
                    ])
                    ->required(),
                Forms\Components\TextInput::make('jumlah')
                    ->numeric()
                    ->required()
                    ->minValue(1),
                Forms\Components\Textarea::make('catatan')
                    ->columnSpanFull(),
            ]);
    }

    public static function table(Table $table): Table
    {
        return $table
            ->columns([
                Tables\Columns\TextColumn::make('produk.nama_produk')
                    ->searchable()
                    ->sortable(),
                Tables\Columns\TextColumn::make('tipe')
                    ->badge()
                    ->color(fn (string $state): string => match ($state) {
                        'masuk' => 'success',
                        'keluar' => 'danger',
                    }),
                Tables\Columns\TextColumn::make('jumlah')
                    ->numeric(),
                Tables\Columns\TextColumn::make('created_at')
                    ->dateTime('d-M-Y H:i')
                    ->label('Tanggal Transaksi')
                    ->sortable(),
            ])
            ->defaultSort('created_at', 'desc');
    }

    // Logika untuk meng-update stok setelah transaksi dibuat
    public static function afterCreate(Model $record, array $data): void
    {
        $produk = Produk::find($data['produk_id']);
        if ($data['tipe'] === 'masuk') {
            $produk->stok += $data['jumlah'];
        } else {
            $produk->stok -= $data['jumlah'];
        }
        $produk->save();
    }

    // Kita hanya mengizinkan melihat dan membuat transaksi untuk menjaga integritas data
    public static function getPages(): array
    {
        return [
            'index' => Pages\ListTransaksis::route('/'),
            'create' => Pages\CreateTransaksi::route('/create'),
        ];
    }
}