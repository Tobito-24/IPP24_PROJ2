<?php

namespace IPP\Student;

use IPP\Core\Interface\InputReader;
use IPP\Core\Interface\OutputWriter;
use IPP\Student\Enums\DataTypeEnum;

class IO //Singleton class to manage IO operations
{
    private InputReader $_stdin;
    private OutputWriter $_stdout;
    private OutputWriter $_stderr;

    public function __construct(InputReader $stdin, OutputWriter $stdout, OutputWriter $stderr)
    {
        $this->_stdin = $stdin;
        $this->_stdout = $stdout;
        $this->_stderr = $stderr;
    }

    public function read(DataTypeEnum $type): string|int|bool|null
    {
        if($type == DataTypeEnum::int) {
            return $this->_stdin->readInt();
        }
        else if($type == DataTypeEnum::bool) {
            return $this->_stdin->readBool();
        }
        else if($type == DataTypeEnum::string) {
            return $this->_stdin->readString();
        }
        return null;
    }

    public function writeStdout(DataTypeEnum $type, string|int|bool $value): void
    {
        if($type == DataTypeEnum::int) {
            if(gettype($value ) == "integer")
            {
                $this->_stdout->writeInt($value);
            }
        }
        else if($type == DataTypeEnum::bool) {
            if(gettype($value) == "boolean")
            {
                $this->_stdout->writeBool($value);
            }
        }
        else if($type == DataTypeEnum::string) {
            if(gettype($value) == "string")
            {
                $this->_stdout->writeString($value);
            }
        }
    }
    public function writeStderr(string $value): void
    {
        $this->_stderr->writeString($value);
    }
}