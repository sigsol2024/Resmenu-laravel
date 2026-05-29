<?php

declare(strict_types=1);

/**
 * Parse phpMyAdmin SQL dump INSERT statements into table => list of row maps.
 */
final class DumpParser
{
    /** @var array<string, list<array<string, mixed>>> */
    private array $tables = [];

    public function __construct(private readonly string $sql) {}

    /**
     * @return array<string, list<array<string, mixed>>>
     */
    public function parse(): array
    {
        $this->tables = [];
        $statements = $this->extractInsertStatements($this->sql);

        foreach ($statements as $statement) {
            if (! preg_match('/^INSERT INTO `([^`]+)`\s*\(([^)]+)\)\s*VALUES\s*(.+)$/is', $statement, $m)) {
                continue;
            }

            $table = $m[1];
            $columns = array_map(
                static fn (string $col) => trim($col, " `\t\n\r"),
                explode(',', $m[2])
            );
            $valueSection = rtrim(trim($m[3]), ';');
            $tuples = $this->parseValueTuples($valueSection);

            foreach ($tuples as $tuple) {
                $values = $this->parseTupleValues($tuple);
                if (count($values) !== count($columns)) {
                    continue;
                }
                $row = array_combine($columns, $values);
                if ($row === false) {
                    continue;
                }
                $this->tables[$table][] = $row;
            }
        }

        return $this->tables;
    }

    /** @return list<string> */
    private function extractInsertStatements(string $sql): array
    {
        $lines = preg_split('/\r\n|\n|\r/', $sql) ?: [];
        $statements = [];
        $buffer = '';
        $inCreate = false;

        foreach ($lines as $line) {
            $trim = trim($line);

            if (preg_match('/^CREATE TABLE/i', $trim)) {
                $inCreate = true;
                continue;
            }
            if ($inCreate) {
                if (str_contains($trim, ';') && ! str_starts_with($trim, 'CREATE')) {
                    $inCreate = false;
                }
                continue;
            }

            if (preg_match('/^INSERT INTO `/i', $trim)) {
                $buffer = $line;
                if (str_ends_with(rtrim($line), ';')) {
                    $statements[] = $buffer;
                    $buffer = '';
                }
                continue;
            }

            if ($buffer !== '') {
                $buffer .= "\n".$line;
                if (str_ends_with(rtrim($line), ';')) {
                    $statements[] = $buffer;
                    $buffer = '';
                }
            }
        }

        return $statements;
    }

    /** @return list<string> */
    private function parseValueTuples(string $valueSection): array
    {
        $tuples = [];
        $len = strlen($valueSection);
        $i = 0;

        while ($i < $len) {
            while ($i < $len && ($valueSection[$i] === ' ' || $valueSection[$i] === ',' || $valueSection[$i] === "\n" || $valueSection[$i] === "\r")) {
                $i++;
            }
            if ($i >= $len) {
                break;
            }
            if ($valueSection[$i] !== '(') {
                break;
            }

            $depth = 0;
            $start = $i;
            $inString = false;
            $escape = false;

            for (; $i < $len; $i++) {
                $ch = $valueSection[$i];

                if ($escape) {
                    $escape = false;
                    continue;
                }

                if ($inString) {
                    if ($ch === '\\') {
                        $escape = true;
                    } elseif ($ch === "'") {
                        $inString = false;
                    }
                    continue;
                }

                if ($ch === "'") {
                    $inString = true;
                    continue;
                }

                if ($ch === '(') {
                    $depth++;
                } elseif ($ch === ')') {
                    $depth--;
                    if ($depth === 0) {
                        $tuples[] = substr($valueSection, $start + 1, $i - $start - 1);
                        $i++;
                        break;
                    }
                }
            }
        }

        return $tuples;
    }

    /** @return list<mixed> */
    private function parseTupleValues(string $tuple): array
    {
        $values = [];
        $len = strlen($tuple);
        $i = 0;

        while ($i < $len) {
            while ($i < $len && ($tuple[$i] === ' ' || $tuple[$i] === ',')) {
                $i++;
            }
            if ($i >= $len) {
                break;
            }

            if ($tuple[$i] === "'") {
                $i++;
                $raw = '';
                while ($i < $len) {
                    $ch = $tuple[$i];
                    if ($ch === '\\' && $i + 1 < $len) {
                        $next = $tuple[$i + 1];
                        $raw .= match ($next) {
                            'n' => "\n",
                            'r' => "\r",
                            't' => "\t",
                            '\\' => '\\',
                            "'" => "'",
                            default => $next,
                        };
                        $i += 2;
                        continue;
                    }
                    if ($ch === "'") {
                        $i++;
                        break;
                    }
                    $raw .= $ch;
                    $i++;
                }
                $values[] = $raw;
                continue;
            }

            $start = $i;
            while ($i < $len && $tuple[$i] !== ',') {
                $i++;
            }
            $token = trim(substr($tuple, $start, $i - $start));
            if ($token === '' || strtoupper($token) === 'NULL') {
                $values[] = null;
            } elseif (is_numeric($token)) {
                $values[] = str_contains($token, '.') ? (float) $token : (int) $token;
            } else {
                $values[] = $token;
            }
        }

        return $values;
    }
}
