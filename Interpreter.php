<?php

namespace IPP\Student;

use IPP\Core\AbstractInterpreter;
use IPP\Core\Exception\XMLException;
use IPP\Student\Exceptions\BadValueException;
use IPP\Student\Exceptions\ExitExc;
use IPP\Student\Exceptions\FrameException;
use IPP\Student\Exceptions\InvalidXMLStructure;
use IPP\Student\Exceptions\MissingValueException;
use IPP\Student\Exceptions\NotExistException;
use IPP\Student\Exceptions\SemanticException;
use IPP\Student\Exceptions\StringException;
use IPP\Student\Exceptions\TypeException;
use IPP\Student\Frames\FrameManager;
use IPP\Student\Instructions\Add;
use IPP\Student\Instructions\AndInst;
use IPP\Student\Instructions\BreakInst;
use IPP\Student\Instructions\Call;
use IPP\Student\Instructions\Concat;
use IPP\Student\Instructions\CreateFrame;
use IPP\Student\Instructions\Defvar;
use IPP\Student\Instructions\DPrint;
use IPP\Student\Instructions\Eq;
use IPP\Student\Instructions\ExitInst;
use IPP\Student\Instructions\GetChar;
use IPP\Student\Instructions\Gt;
use IPP\Student\Instructions\Idiv;
use IPP\Student\Instructions\InstructionInterface;
use IPP\Student\Instructions\IntToChar;
use IPP\Student\Instructions\Jump;
use IPP\Student\Instructions\JumpIfEq;
use IPP\Student\Instructions\JumpIfNeq;
use IPP\Student\Instructions\LabelInst;
use IPP\Student\Instructions\Lt;
use IPP\Student\Instructions\Move;
use IPP\Student\Instructions\Mul;
use IPP\Student\Instructions\Not;
use IPP\Student\Instructions\OrInst;
use IPP\Student\Instructions\PopFrame;
use IPP\Student\Instructions\Pops;
use IPP\Student\Instructions\PushFrame;
use IPP\Student\Instructions\Pushs;
use IPP\Student\Instructions\Read;
use IPP\Student\Instructions\ReturnInst;
use IPP\Student\Instructions\SetChar;
use IPP\Student\Instructions\Str2Int;
use IPP\Student\Instructions\Strlen;
use IPP\Student\Instructions\Sub;
use IPP\Student\Instructions\Type;
use IPP\Student\Instructions\Write;
use IPP\Student\Operands\Label;
use IPP\Student\Operands\LabelManager;
use IPP\Student\Operands\Symbol;

class Interpreter extends AbstractInterpreter
{
    /**
     * @throws TypeException
     * @throws MissingValueException
     * @throws NotExistException
     * @throws XMLException
     * @throws FrameException
     * @throws StringException
     * @throws InvalidXMLStructure
     * @throws BadValueException
     * @throws SemanticException
     */
    public function execute(): int
    {

        // TODO: Start your code here
        $dom = $this->source->getDOMDocument();
        $program = $dom->documentElement; // root element
        if($program == null)
        {
            return 0;
        }
        foreach ($program->childNodes as $childNode) { // check if there are any other elements than instruction
            if ($childNode->nodeType === XML_ELEMENT_NODE && $childNode->nodeName !== 'instruction') {
                throw new InvalidXMLStructure;
            }
        }
        if($program->getAttribute("language") != "IPPcode24") // check if language is IPPcode24
        {
            throw new InvalidXMLStructure;
        }
        $instructions = $dom->getElementsByTagName('instruction'); // get all instructions
        FrameManager::getInstance(); // create frame manager
        $IO = new IO($this->input, $this->stdout, $this->stderr); // create IO
        $LabelManager = LabelManager::getInstance(); // create label manager
        $instArray = array(); // array of instructions to execute
        foreach ($instructions as $instruction) {
            $opcode = $instruction->getAttribute('opcode'); // get opcode
            $opcode = strtoupper($opcode); // convert to uppercase
            $opOrder = $instruction->getAttribute('order'); // get order
            $opOrder = intval($opOrder); // convert to int
            if ($opcode == "" || $opOrder == "" || $opOrder < 1) // check if opcode is empty or order is less than 1
            {
                throw new InvalidXMLStructure;
            }
            if(array_key_exists($opOrder, $instArray)) // check if order already exists
            {
                throw new InvalidXMLStructure;
            }
            $args = $instruction->getElementsByTagName(''); // get all arguments
            $arg1 = $instruction->getElementsByTagName('arg1');
            $arg2 = $instruction->getElementsByTagName('arg2');
            $arg3 = $instruction->getElementsByTagName('arg3');
            if($arg1->length > 1 || $arg2->length > 1 || $arg3->length > 1) // check if there are more than 1 arguments of each number
            {
                throw new InvalidXMLStructure;
            }
            $arg1 = $arg1[0]; // get the elements
            $arg2 = $arg2[0];
            $arg3 = $arg3[0];
            $arg1value = "";
            $arg2value = "";
            $arg3value = "";
            if($arg1 != null) // get the values of arguments
            {
                $arg1value = $arg1->nodeValue;
                if($arg1value != null)
                {
                    $arg1value = trim($arg1value);
                }
            }
            if($arg2 != null)
            {
                $arg2value = $arg2->nodeValue;
                if($arg2value != null)
                {
                    $arg2value = trim($arg2value);
                }
            }
            if($arg3 != null)
            {
                $arg3value = $arg3->nodeValue;
                if($arg3value != null)
                {
                    $arg3value = trim($arg3value);
                }
            }
            if(gettype($arg1value) != "string" || gettype($arg2value) != "string" || gettype($arg3value) != "string")
            {
                throw new InvalidXMLStructure;
            }
            $arg1value = strval($arg1value); // convert to string
            $arg2value = strval($arg2value);
            $arg3value = strval($arg3value);
            switch ($opcode): // check the opcode, create the instruction object and put it in the array of instructions to execute
            case 'RETURN':
                if($arg1 != null || $arg2 != null || $arg3 != null || $args->length > 1)
                {
                    throw new InvalidXMLStructure;
                }
                $return = new ReturnInst();
                $instArray[$opOrder] = $return;
                break;
            case 'DEFVAR':
                if($arg2 != null || $arg3 != null || $args->length > 1 || $arg1 == null)
                {
                    throw new InvalidXMLStructure;
                }
                $var = $arg1value;
                $type = $arg1->getAttribute('type');
                if($var == "" || $type != 'var')
                {
                    throw new InvalidXMLStructure;
                }
                $defvar = new Defvar($var);
                $instArray[$opOrder] = $defvar;
                break;
            case 'PUSHS':
            case 'EXIT':
                if($arg2 != null || $arg3 != null || $args->length > 1 || $arg1 == null)
                {
                    throw new InvalidXMLStructure;
                }
                $symb = $arg1value;
                if($symb == "")
                {
                    throw new InvalidXMLStructure;
                }
                $symbType = Symbol::getTypeFromStr($arg1->getAttribute('type'));
                if($opcode == 'EXIT')
                {
                    $exit = new ExitInst($symb, $symbType);
                    $instArray[$opOrder] = $exit;
                }
                else
                {
                    $pushs = new Pushs($symbType, $symb);
                    $instArray[$opOrder] = $pushs;
                }
                break;
            case 'POPS':
                if($arg2 != null || $arg3 != null || $args->length > 1 || $arg1 == null)
                {
                    throw new InvalidXMLStructure;
                }
                $var = $arg1value;
                $type = $arg1->getAttribute('type');
                if($var == "" || $type != 'var')
                {
                    throw new InvalidXMLStructure;
                }
                $pops = new Pops($var);
                $instArray[$opOrder] = $pops;
                break;
            case 'LABEL':
            case 'JUMP':
            case 'CALL':
                if($arg2 != null || $arg3 != null || $args->length > 1 || $arg1 == null)
                {
                    throw new InvalidXMLStructure;
                }
                $labelName = $arg1value;
                $type = $arg1->getAttribute('type');
                if($labelName == "" || $type != 'label')
                {
                    throw new InvalidXMLStructure;
                }
                switch ($opcode):
                    case 'JUMP':
                        $jump = new Jump($labelName);
                        $instArray[$opOrder] = $jump;
                        break;
                    case 'CALL':
                        $call = new Call($labelName, $opOrder);
                        $instArray[$opOrder] = $call;
                        break;
                    case 'LABEL':
                        $label = new LabelInst($labelName, $opOrder);
                        $label->do();
                        $instArray[$opOrder] = $label;
                        break;
                endswitch;
                break;
            case 'MOVE':
            case 'NOT':
            case 'INT2CHAR':
            case 'STRLEN':
            case 'TYPE':
                if($arg3 != null || $args->length > 2 || $arg2 == null || $arg1 == null)
                {
                    throw new InvalidXMLStructure;
                }
                $var = $arg1value;
                $type = $arg1->getAttribute('type');
                $symb = $arg2value;
                if($arg1 == "" || $arg2 == "" || $type != 'var')
                {
                    throw new InvalidXMLStructure;
                }
                $symbType = Symbol::getTypeFromStr($arg2->getAttribute('type'));
                switch ($opcode):
                    case 'MOVE':
                        $move = new Move($var, $symb, $symbType);
                        $instArray[$opOrder] = $move;
                        break;
                    case 'NOT':
                        $not = new Not($var, $symb, $symbType);
                        $instArray[$opOrder] = $not;
                        break;
                    case 'INT2CHAR':
                        $inttochar = new IntToChar($var, $symb, $symbType);
                        $instArray[$opOrder] = $inttochar;
                        break;
                    case 'STRLEN':
                        $strlen = new Strlen($var, $symb, $symbType);
                        $instArray[$opOrder] = $strlen;
                        break;
                    case 'TYPE':
                        $type = new Type($var, $symb, $symbType);
                        $instArray[$opOrder] = $type;
                        break;
                endswitch;
                break;
            case 'WRITE':
                if($arg2 != null || $arg3 != null || $args->length > 1 || $arg1 == null)
                {
                    throw new InvalidXMLStructure;
                }
                $symb = $arg1value;
                if($symb == "")
                {
                    throw new InvalidXMLStructure;
                }
                $symbType = Symbol::getTypeFromStr($instruction->getElementsByTagName('arg1')[0]->getAttribute('type'));
                $write = new Write($symb, $symbType, $IO);
                $instArray[$opOrder] = $write;
                break;
            case 'READ':
                if($arg3 != null || $args->length > 2)
                {
                    throw new InvalidXMLStructure;
                }
                $var = $arg1value;
                $type = $arg1->getAttribute('type');
                if($var == "" || $type != 'var')
                {
                    throw new InvalidXMLStructure;
                }
                $type = $arg2value;
                if($type != 'int' && $type != 'string' && $type != 'bool')
                {
                    throw new InvalidXMLStructure;
                }
                $type = Symbol::getTypeFromStr($type);
                $read = new Read($var, $type, $IO);
                $instArray[$opOrder] = $read;
                break;
            case 'CREATEFRAME':
                if($args->length > 0)
                {
                    throw new InvalidXMLStructure;
                }
                $createFrame = new CreateFrame();
                $instArray[$opOrder] = $createFrame;
                break;
            case 'PUSHFRAME':
                if($args->length > 0)
                {
                    throw new InvalidXMLStructure;
                }
                $pushFrame = new PushFrame();
                $instArray[$opOrder] = $pushFrame;
                break;
            case 'POPFRAME':
                if($args->length > 0)
                {
                    throw new InvalidXMLStructure;
                }
                $popFrame = new PopFrame();
                $instArray[$opOrder] = $popFrame;
                break;
            case 'ADD':
            case 'SUB':
            case 'MUL':
            case 'IDIV':
            case 'LT':
            case 'GT':
            case 'EQ':
            case 'AND':
            case 'OR':
            case 'STRI2INT':
            case 'CONCAT':
            case 'GETCHAR':
            case 'SETCHAR':
                if($args->length > 3 || $arg1 == null || $arg2 == null || $arg3 == null || $arg1value == null)
                {
                    throw new InvalidXMLStructure;
                }
                $var = $arg1value;
                $varType = $arg1->getAttribute('type');
                $symb = $arg2value;
                $symbType1 = Symbol::getTypeFromStr($arg2->getAttribute('type'));
                $symb2 = $arg3value;
                $symbType2 = Symbol::getTypeFromStr($arg3->getAttribute('type'));
                if($symb == "" || $symb2 == "" || $varType != 'var')
                {
                    throw new InvalidXMLStructure;
                }
                switch ($opcode):
                    case 'ADD':
                        $add = new Add($var, $symb, $symbType1, $symb2, $symbType2);
                        $instArray[$opOrder] = $add;
                        break;
                    case 'SUB':
                        $sub = new Sub($var, $symb, $symbType1, $symb2, $symbType2);
                        $instArray[$opOrder] = $sub;
                        break;
                    case 'MUL':
                        $mul = new Mul($var, $symb, $symbType1, $symb2, $symbType2);
                        $instArray[$opOrder] = $mul;
                        break;
                    case 'IDIV':
                        $idiv = new Idiv($var, $symb, $symbType1, $symb2, $symbType2);
                        $instArray[$opOrder] = $idiv;
                        break;
                    case 'LT':
                        $lt = new Lt($var, $symb, $symbType1, $symb2, $symbType2);
                        $instArray[$opOrder] = $lt;
                        break;
                    case 'GT':
                        $gt = new Gt($var, $symb, $symbType1, $symb2, $symbType2);
                        $instArray[$opOrder] = $gt;
                        break;
                    case 'EQ':
                        $eq = new Eq($var, $symb, $symbType1, $symb2, $symbType2);
                        $instArray[$opOrder] = $eq;
                        break;
                    case 'AND':
                        $and = new AndInst($var, $symb, $symbType1, $symb2, $symbType2);
                        $instArray[$opOrder] = $and;
                        break;
                    case 'OR':
                        $or = new OrInst($var, $symb, $symbType1, $symb2, $symbType2);
                        $instArray[$opOrder] = $or;
                        break;
                    case 'STRI2INT':
                        $str2int = new Str2Int($var, $symb, $symbType1, $symb2, $symbType2);
                        $instArray[$opOrder] = $str2int;
                        break;
                    case 'CONCAT':
                        $concat = new Concat($var, $symb, $symbType1, $symb2, $symbType2);
                        $instArray[$opOrder] = $concat;
                        break;
                    case 'GETCHAR':
                        $getchar = new GetChar($var, $symb, $symbType1, $symb2, $symbType2);
                        $instArray[$opOrder] = $getchar;
                        break;
                    case 'SETCHAR':
                        $setchar = new SetChar($var, $symb, $symbType1, $symb2, $symbType2);
                        $instArray[$opOrder] = $setchar;
                        break;
                        endswitch;
                    break;
                case 'JUMPIFEQ':
                case 'JUMPIFNEQ':
                    if($args->length > 3 || $arg1 == null || $arg2 == null || $arg3 == null || $arg1value == null)
                    {
                        throw new InvalidXMLStructure;
                    }
                    $labelName = $arg1value;
                    $type = $arg1->getAttribute('type');
                    $symb = $arg2value;
                    $symbType1 = Symbol::getTypeFromStr($arg2->getAttribute('type'));
                    $symb2 = $arg3value;
                    $symbType2 = Symbol::getTypeFromStr($arg3->getAttribute('type'));
                    if($symb == "" || $symb2 == "" || $type != 'label')
                    {
                        throw new InvalidXMLStructure;
                    }
                    if($opcode == 'JUMPIFEQ')
                    {
                        $jumpifeq = new JumpIfEq($labelName, $symb, $symb2, $symbType1, $symbType2);
                        $instArray[$opOrder] = $jumpifeq;
                    }
                    else
                    {
                        $jumpifneq = new JumpIfNeq($labelName, $symb, $symb2, $symbType1, $symbType2);
                        $instArray[$opOrder] = $jumpifneq;
                    }
                    break;
                case 'DPRINT':
                    if($arg2 != null || $arg3 != null || $args->length > 1 || $arg1 == null)
                    {
                        throw new InvalidXMLStructure;
                    }
                    $symb = $arg1value;
                    if($symb == "")
                    {
                        throw new InvalidXMLStructure;
                    }
                    $symbType = Symbol::getTypeFromStr($arg1->getAttribute('type'));
                    $dprint = new DPrint($symb, $symbType, $IO);
                    $instArray[$opOrder] = $dprint;
                    break;
                case 'BREAK':
                    if($args->length > 0)
                    {
                        throw new InvalidXMLStructure;
                    }
                    $break = new BreakInst($IO);
                    $instArray[$opOrder] = $break;
                    break;
                default:
                    throw new InvalidXMLStructure;
            endswitch;
        }
        ksort($instArray); // sort the array by order
        if(count($instArray) == 0) // if there are no instructions to execute, return 0
        {
            return 0;
        }
        $executionManager = ExecutionManager::setInstance($instArray); // create execution manager
        try{ // execute the instructions
            $executionManager->execute();
        }
        catch(ExitExc $e) // catch exit exception to return with the code set by the EXIT instruction
        {
            $retCode = $e->getCode();
            if($retCode < 0 || $retCode > 9)
            {
                throw new BadValueException;
            }
            return $retCode;
        }

        return 0;
    }
}
