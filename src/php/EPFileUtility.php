<?php
declare(strict_types=1);

namespace EclipsePhaseCharacterCreator\Backend;

/**
 * A utility to help convert Eclipse Phase characters to files.
 */
class EPFileUtility
{

    /**
     * @var EPCharacter
     */
    private $character;

    /**
     * Constructor
     * @param EPCharacter $character the character that is currently being created
     */
    public function __construct(EPCharacter $character)
    {
        $this->character = $character;
    }

    /**
     * Build a filename to be used when exporting an EP character to
     * another format (e.g., JSON, text, PDF, etc).
     *
     * The filename will be composed of the character name, the current
     * date and time, and a file extension.
     *
     * @param string $defaultName Base filename to use if the character name is blank.
     * @param string $extension   The file extension
     * @return string a filename
     */
    public function buildExportFilename(string $defaultName, string $extension): string
    {
        $character_name = trim($this->character->charName);
        if ('' !== $character_name) {
            $filename = $character_name;
        } else {
            $filename = $defaultName;
        }

        // append date, time and file extension to save name
        $filename .= '-' . date('Ymd-His') . '.' . $extension;

        return $this->sanitizeFilename($filename);
    }

    /**
     * Strip characters from a filename that may not be compatible with the filesystem.
     * @param string $filename a string to be used as a filename
     * @return string a sanitized version of $filename
     */
    public function sanitizeFilename(string $filename): string
    {
        return preg_replace('/[^a-zA-Z0-9_.-]/', '', $filename);
    }
}