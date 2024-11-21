<?php

namespace App\Filament\Resources\EBooksResource\Pages;

use App\Filament\Resources\EBooksResource;
use Filament\Resources\Pages\CreateRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class CreateEBooks extends CreateRecord
{
    protected static string $resource = EBooksResource::class;

    protected function handleRecordCreation(array $data): Model
    {
        $lccClassification = [
            'category_id' => $data['category_id'],
            'subcategories' => $data['subcategories'],
            'code' => $data['code'],
        ];
        Log::info('Published Data:', ['published' => $data['published']]);

        $ebook = static::getModel()::create([
            'lcc_classification' => json_encode($lccClassification),
            'category_id' => $data['category_id'],
            'title' => $data['title'],
            'slug' => $data['slug'],
            'author' => $data['author'],
            'publication_year' => $data['publication_year'],
            'publisher' => $data['publisher'],
            'isbn' => $data['isbn'],
            'language' => $data['language'],
            'description' => $data['description'],
            'book_cover' => $data['book_cover'],
            'ebook_file_path' => $data['ebook_file_path'],
            'published' => $data['published'],
            
        ]);
       

        $ebook->subcategories()->attach($data['subcategories']);

        return $ebook;
    }
}
