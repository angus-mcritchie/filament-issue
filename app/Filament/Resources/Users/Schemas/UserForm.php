<?php

namespace App\Filament\Resources\Users\Schemas;

use App\Models\User;
use Filament\Forms\Components\DateTimePicker;
use Filament\Forms\Components\RichEditor;
use Filament\Forms\Components\RichEditor\RichContentRenderer;
use Filament\Forms\Components\TextInput;
use Filament\Schemas\Components\Grid;
use Filament\Schemas\Schema;

class UserForm
{
    public static function configure(Schema $schema): Schema
    {
        return $schema
            ->columns(1)
            ->components([
                Grid::make(3)
                    ->schema([
                        TextInput::make('name')
                            ->required(),

                        TextInput::make('email')
                            ->email()
                            ->required(),

                        DateTimePicker::make('email_verified_at'),
                    ]),

                RichEditor::make('bio')
                    ->maxLength(10)

                    /**
                     * Optional helper text to show the length of the plain text and HTML content.
                     * This will update dynamically as the user types in the editor.
                     */
                    ->helperText(function (?User $record = null): ?string {
                        if (!$record) {
                            return "Plain text length: 0 characters. HTML length: 0 characters.";
                        }

                        $html = RichContentRenderer::make($record->bio)->toHtml();
                        $text = str($html)->stripTags();

                        return "Plain text length: " . $text->length() . " characters. HTML length: " . str($html)->length() . " characters.";
                    })
                    ->afterStateUpdatedJs("
                        const editor = \$el.querySelector('.ProseMirror').editor;
                        const html = editor ? editor.getHTML() : (\$state || '');

                        function htmlToText(html) {
                            const div = document.createElement('div');
                            div.innerHTML = html;
                            return div.textContent || div.innerText || '';
                        }

                        const text = htmlToText(html);
                        const helperTextElement = \$el.querySelector('.fi-sc .fi-sc-text');

                        console.log({html, text},editor);

                        if (helperTextElement) {
                            helperTextElement.textContent = 'Plain text length: ' + text.length + ' characters. HTML length: ' + html.length + ' characters.';
                        } else {
                            console.warn('Helper text element not found.',\$el);
                        }
                    ")
            ]);
    }
}
