<?php

namespace Seal\LaravelDataMapper\Attributes\Column;

enum Type: string
{
    case CHAR = 'CHAR';
    case VARCHAR = 'VARCHAR';
    case TINYTEXT = 'TINYTEXT';
    case TEXT = 'TEXT';
    case MEDIUMTEXT = 'MEDIUMTEXT';
    case LONGTEXT = 'LONGTEXT';
    case TINYINT = 'TINYINT';
    case BOOL = 'BOOL';
    case TINYINT_UNSIGNED = 'TINYINT_UNSIGNED';
    case SMALLINT = 'SMALLINT';
    case SMALLINT_UNSIGNED = 'SMALLINT_UNSIGNED';
    case BIGINT = 'BIGINT';
    case BIGINT_UNSIGNED = 'BIGINT_UNSIGNED';
    case DECIMAL = 'DECIMAL';
    case FLOAT = 'FLOAT';
    case DOUBLE = 'DOUBLE';
    case DATE = 'DATE';
    case TIME = 'TIME';
    case DATETIME = 'DATETIME';
    case TIMESTAMP = 'TIMESTAMP';
    case ENUM = 'ENUM';
    case SET = 'SET';
    case TINYBLOB = 'TINYBLOB';
    case BLOB = 'BLOB';
    case MEDIUMBLOB = 'MEDIUMBLOB';
    case LONGBLOB = 'LONGBLOB';
}
