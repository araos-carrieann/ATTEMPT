<?php

namespace App\Filament\Resources\EBooksResource\Pages;

use App\Filament\Resources\EBooksResource;
use App\Models\EBooks;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Log;

class EditEBooks extends EditRecord
{
    protected function mutateFormDataBeforeFill(array $data): array
    {
        // Retrieve the book ID from the $data array
        $bookId = $data['id'] ?? null;
    
        // Initialize the $subcategories array
        $subcategories = [];
    
        // Retrieve the specific book record if the book ID is provided
        if ($bookId) {
            $book = eBooks::find($bookId);
    
            if ($book) {
                // Assuming subcategories are IDs or names, adjust this based on your model
                $subcategories = $book->subcategories->pluck('id')->toArray(); // Use 'name' if subcategories are names
                
                // Update $data with additional details from the book
                $data['title'] = $book->title;
                $data['category_id'] = $book->category_id;
                $data['slug'] = $book->slug;
                $data['author'] = $book->author;
                $data['publication_year'] = $book->publication_year;
                $data['publisher'] = $book->publisher;
                $data['isbn'] = $book->isbn;
                $data['language'] = $book->language;
                $data['description'] = $book->description;
                $data['book_cover'] = $book->book_cover;
                $data['ebook_file_path'] = $book->ebook_file_path;
                $data['published'] = $book->published;
            }
        }
    
        // Decode the existing lcc_classification field if it exists
        $existingClassification = $data['lcc_classification'] ?? '{}';
        $classificationData = json_decode($existingClassification, true);
    
        // Check if decoding was successful
        if (json_last_error() !== JSON_ERROR_NONE) {
            Log::error('JSON decode error: ' . json_last_error_msg());
        }
    
        // Log the decoded classification data for debugging
        Log::info('Decoded classification data:', $classificationData);
    
        // Separate fields
        $data['category_id'] = $classificationData['category_id'] ?? $data['category_id'];
        $data['subcategories'] = $classificationData['subcategories'] ?? $subcategories;
        $data['code'] = $classificationData['code'] ?? $data['code'];
    
        // Return the modified data
        return $data;
    }
    


    protected static string $resource = EBooksResource::class;

    // Make sure that handleRecordUpdate method matches the parent's method signature
    protected function handleRecordUpdate(Model $record, array $data): Model
    {
        // Retrieve the existing record
        $record = static::getModel()::find($this->record->getKey());

        // Check if record exists
        if (!$record) {
            throw new \Exception('Record not found');
        }

        // Update the Ebook record
        $record->update([
            'title' => $data['title'],
            'category_id' => $data['category_id'],
            'slug' => $data['slug'],
            'author' => $data['author'],
            'publication_year' => $data['publication_year'],
            'publisher' => $data['publisher'],
            'isbn' => $data['isbn'],
            'language' => $data['language'],
            'description' => $data['description'],
            'book_cover' => $data['book_cover'],
            'ebook_file_path' => $data['ebook_file_path'],
            'lcc_classification' => json_encode([
                'category_id' => $data['category_id'],
                'subcategories' => $data['subcategories'],
                'code' => $data['code'],
            ]),
        ]);

        // Sync subcategories with the ebook
        $record->subcategories()->sync($data['subcategories']);

        return $record;
    }
}
