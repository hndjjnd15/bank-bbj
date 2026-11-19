<?php

namespace App\Filament\Resources\Faqs\Schemas;

use App\Models\Faq;
use App\Models\ProdukBank;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Schemas\Components\Utilities\Get;
use Filament\Schemas\Components\Utilities\Set;
use Filament\Schemas\Schema;
use Illuminate\Validation\Rule;

class FaqForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Select::make('halaman')
                    ->label('Tampilkan di')
                    ->options(Faq::HALAMAN)
                    ->required()
                    ->native(false)
                    ->live()
                    ->afterStateUpdated(fn (Set $set) => $set('produk_bank_id', null)),

                Select::make('produk_bank_id')
                    ->label('Khusus produk kredit')
                    ->options(fn () => ProdukBank::query()
                        ->where('jenis', 'kredit')
                        ->orderBy('judul')
                        ->pluck('judul', 'id')
                        ->all())
                    ->searchable()
                    ->preload()
                    ->native(false)
                    ->nullable()
                    ->required(fn (Get $get): bool => $get('halaman') === 'kredit')
                    ->visible(fn (Get $get): bool => $get('halaman') === 'kredit')
                    ->rules(fn (Get $get): array => $get('halaman') === 'kredit'
                        ? [Rule::exists('produk_banks', 'id')->where('jenis', 'kredit')]
                        : ['nullable'])
                    ->helperText('FAQ hanya tampil pada produk kredit yang dipilih.')
                    ->columnSpanFull(),

                TextInput::make('tanya')
                    ->label('Pertanyaan')
                    ->required()
                    ->maxLength(255)
                    ->columnSpanFull(),

                Textarea::make('jawab')
                    ->label('Jawaban')
                    ->required()
                    ->rows(5)
                    ->columnSpanFull(),

                TextInput::make('urutan')
                    ->label('Urutan tampil')
                    ->numeric()
                    ->integer()
                    ->minValue(0)
                    ->default(0)
                    ->required(),

                Toggle::make('diterbitkan')
                    ->label('Tampilkan di website')
                    ->default(true),
            ]);
    }
}
