<?php
namespace LCloss\DB;

use LCloss\DB\Connection;
use \PDO;
use \PDOStatement;

class Database
{
    private static $instance = NULL;
    private static $collates = [
        'big5_chinese_ci' => 'big5',
        'big5_bin' => 'big5',
        'dec8_swedish_ci' => 'dec8',
        'dec8_bin' => 'dec8',
        'cp850_general_ci' => 'cp850',
        'cp850_bin' => 'cp850',
        'hp8_english_ci' => 'hp8',
        'hp8_bin' => 'hp8',
        'koi8r_general_ci' => 'koi8r',
        'koi8r_bin' => 'koi8r',
        'latin1_german1_ci' => 'latin1',
        'latin1_swedish_ci' => 'latin1',
        'latin1_danish_ci' => 'latin1',
        'latin1_german2_ci' => 'latin1',
        'latin1_bin' => 'latin1',
        'latin1_general_ci' => 'latin1',
        'latin1_general_cs' => 'latin1',
        'latin1_spanish_ci' => 'latin1',
        'latin2_czech_cs' => 'latin2',
        'latin2_general_ci' => 'latin2',
        'latin2_hungarian_ci' => 'latin2',
        'latin2_croatian_ci' => 'latin2',
        'latin2_bin' => 'latin2',
        'swe7_swedish_ci' => 'swe7',
        'swe7_bin' => 'swe7',
        'ascii_general_ci' => 'ascii',
        'ascii_bin' => 'ascii',
        'ujis_japanese_ci' => 'ujis',
        'ujis_bin' => 'ujis',
        'sjis_japanese_ci' => 'sjis',
        'sjis_bin' => 'sjis',
        'hebrew_general_ci' => 'hebrew',
        'hebrew_bin' => 'hebrew',
        'tis620_thai_ci' => 'tis620',
        'tis620_bin' => 'tis620',
        'euckr_korean_ci' => 'euckr',
        'euckr_bin' => 'euckr',
        'koi8u_general_ci' => 'koi8u',
        'koi8u_bin' => 'koi8u',
        'gb2312_chinese_ci' => 'gb2312',
        'gb2312_bin' => 'gb2312',
        'greek_general_ci' => 'greek',
        'greek_bin' => 'greek',
        'cp1250_general_ci' => 'cp1250',
        'cp1250_czech_cs' => 'cp1250',
        'cp1250_croatian_ci' => 'cp1250',
        'cp1250_bin' => 'cp1250',
        'cp1250_polish_ci' => 'cp1250',
        'gbk_chinese_ci' => 'gbk',
        'gbk_bin' => 'gbk',
        'latin5_turkish_ci' => 'latin5',
        'latin5_bin' => 'latin5',
        'armscii8_general_ci' => 'armscii8',
        'armscii8_bin' => 'armscii8',
        'utf8_general_ci' => 'utf8',
        'utf8_bin' => 'utf8',
        'utf8_unicode_ci' => 'utf8',
        'utf8_icelandic_ci' => 'utf8',
        'utf8_latvian_ci' => 'utf8',
        'utf8_romanian_ci' => 'utf8',
        'utf8_slovenian_ci' => 'utf8',
        'utf8_polish_ci' => 'utf8',
        'utf8_estonian_ci' => 'utf8',
        'utf8_spanish_ci' => 'utf8',
        'utf8_swedish_ci' => 'utf8',
        'utf8_turkish_ci' => 'utf8',
        'utf8_czech_ci' => 'utf8',
        'utf8_danish_ci' => 'utf8',
        'utf8_lithuanian_ci' => 'utf8',
        'utf8_slovak_ci' => 'utf8',
        'utf8_spanish2_ci' => 'utf8',
        'utf8_roman_ci' => 'utf8',
        'utf8_persian_ci' => 'utf8',
        'utf8_esperanto_ci' => 'utf8',
        'utf8_hungarian_ci' => 'utf8',
        'utf8_sinhala_ci' => 'utf8',
        'utf8_german2_ci' => 'utf8',
        'utf8_croatian_ci' => 'utf8',
        'utf8_unicode_520_ci' => 'utf8',
        'utf8_vietnamese_ci' => 'utf8',
        'utf8_general_mysql500_ci' => 'utf8',
        'ucs2_general_ci' => 'ucs2',
        'ucs2_bin' => 'ucs2',
        'ucs2_unicode_ci' => 'ucs2',
        'ucs2_icelandic_ci' => 'ucs2',
        'ucs2_latvian_ci' => 'ucs2',
        'ucs2_romanian_ci' => 'ucs2',
        'ucs2_slovenian_ci' => 'ucs2',
        'ucs2_polish_ci' => 'ucs2',
        'ucs2_estonian_ci' => 'ucs2',
        'ucs2_spanish_ci' => 'ucs2',
        'ucs2_swedish_ci' => 'ucs2',
        'ucs2_turkish_ci' => 'ucs2',
        'ucs2_czech_ci' => 'ucs2',
        'ucs2_danish_ci' => 'ucs2',
        'ucs2_lithuanian_ci' => 'ucs2',
        'ucs2_slovak_ci' => 'ucs2',
        'ucs2_spanish2_ci' => 'ucs2',
        'ucs2_roman_ci' => 'ucs2',
        'ucs2_persian_ci' => 'ucs2',
        'ucs2_esperanto_ci' => 'ucs2',
        'ucs2_hungarian_ci' => 'ucs2',
        'ucs2_sinhala_ci' => 'ucs2',
        'ucs2_german2_ci' => 'ucs2',
        'ucs2_croatian_ci' => 'ucs2',
        'ucs2_unicode_520_ci' => 'ucs2',
        'ucs2_vietnamese_ci' => 'ucs2',
        'ucs2_general_mysql500_ci' => 'ucs2',
        'cp866_general_ci' => 'cp866',
        'cp866_bin' => 'cp866',
        'keybcs2_general_ci' => 'keybcs2',
        'keybcs2_bin' => 'keybcs2',
        'macce_general_ci' => 'macce',
        'macce_bin' => 'macce',
        'macroman_general_ci' => 'macroman',
        'macroman_bin' => 'macroman',
        'cp852_general_ci' => 'cp852',
        'cp852_bin' => 'cp852',
        'latin7_estonian_cs' => 'latin7',
        'latin7_general_ci' => 'latin7',
        'latin7_general_cs' => 'latin7',
        'latin7_bin' => 'latin7',
        'utf8mb4_general_ci' => 'utf8mb4',
        'utf8mb4_bin' => 'utf8mb4',
        'utf8mb4_unicode_ci' => 'utf8mb4',
        'utf8mb4_icelandic_ci' => 'utf8mb4',
        'utf8mb4_latvian_ci' => 'utf8mb4',
        'utf8mb4_romanian_ci' => 'utf8mb4',
        'utf8mb4_slovenian_ci' => 'utf8mb4',
        'utf8mb4_polish_ci' => 'utf8mb4',
        'utf8mb4_estonian_ci' => 'utf8mb4',
        'utf8mb4_spanish_ci' => 'utf8mb4',
        'utf8mb4_swedish_ci' => 'utf8mb4',
        'utf8mb4_turkish_ci' => 'utf8mb4',
        'utf8mb4_czech_ci' => 'utf8mb4',
        'utf8mb4_danish_ci' => 'utf8mb4',
        'utf8mb4_lithuanian_ci' => 'utf8mb4',
        'utf8mb4_slovak_ci' => 'utf8mb4',
        'utf8mb4_spanish2_ci' => 'utf8mb4',
        'utf8mb4_roman_ci' => 'utf8mb4',
        'utf8mb4_persian_ci' => 'utf8mb4',
        'utf8mb4_esperanto_ci' => 'utf8mb4',
        'utf8mb4_hungarian_ci' => 'utf8mb4',
        'utf8mb4_sinhala_ci' => 'utf8mb4',
        'utf8mb4_german2_ci' => 'utf8mb4',
        'utf8mb4_croatian_ci' => 'utf8mb4',
        'utf8mb4_unicode_520_ci' => 'utf8mb4',
        'utf8mb4_vietnamese_ci' => 'utf8mb4',
        'cp1251_bulgarian_ci' => 'cp1251',
        'cp1251_ukrainian_ci' => 'cp1251',
        'cp1251_bin' => 'cp1251',
        'cp1251_general_ci' => 'cp1251',
        'cp1251_general_cs' => 'cp1251',
        'utf16_general_ci' => 'utf16',
        'utf16_bin' => 'utf16',
        'utf16_unicode_ci' => 'utf16',
        'utf16_icelandic_ci' => 'utf16',
        'utf16_latvian_ci' => 'utf16',
        'utf16_romanian_ci' => 'utf16',
        'utf16_slovenian_ci' => 'utf16',
        'utf16_polish_ci' => 'utf16',
        'utf16_estonian_ci' => 'utf16',
        'utf16_spanish_ci' => 'utf16',
        'utf16_swedish_ci' => 'utf16',
        'utf16_turkish_ci' => 'utf16',
        'utf16_czech_ci' => 'utf16',
        'utf16_danish_ci' => 'utf16',
        'utf16_lithuanian_ci' => 'utf16',
        'utf16_slovak_ci' => 'utf16',
        'utf16_spanish2_ci' => 'utf16',
        'utf16_roman_ci' => 'utf16',
        'utf16_persian_ci' => 'utf16',
        'utf16_esperanto_ci' => 'utf16',
        'utf16_hungarian_ci' => 'utf16',
        'utf16_sinhala_ci' => 'utf16',
        'utf16_german2_ci' => 'utf16',
        'utf16_croatian_ci' => 'utf16',
        'utf16_unicode_520_ci' => 'utf16',
        'utf16_vietnamese_ci' => 'utf16',
        'utf16le_general_ci' => 'utf16le',
        'utf16le_bin' => 'utf16le',
        'cp1256_general_ci' => 'cp1256',
        'cp1256_bin' => 'cp1256',
        'cp1257_lithuanian_ci' => 'cp1257',
        'cp1257_bin' => 'cp1257',
        'cp1257_general_ci' => 'cp1257',
        'utf32_general_ci' => 'utf32',
        'utf32_bin' => 'utf32',
        'utf32_unicode_ci' => 'utf32',
        'utf32_icelandic_ci' => 'utf32',
        'utf32_latvian_ci' => 'utf32',
        'utf32_romanian_ci' => 'utf32',
        'utf32_slovenian_ci' => 'utf32',
        'utf32_polish_ci' => 'utf32',
        'utf32_estonian_ci' => 'utf32',
        'utf32_spanish_ci' => 'utf32',
        'utf32_swedish_ci' => 'utf32',
        'utf32_turkish_ci' => 'utf32',
        'utf32_czech_ci' => 'utf32',
        'utf32_danish_ci' => 'utf32',
        'utf32_lithuanian_ci' => 'utf32',
        'utf32_slovak_ci' => 'utf32',
        'utf32_spanish2_ci' => 'utf32',
        'utf32_roman_ci' => 'utf32',
        'utf32_persian_ci' => 'utf32',
        'utf32_esperanto_ci' => 'utf32',
        'utf32_hungarian_ci' => 'utf32',
        'utf32_sinhala_ci' => 'utf32',
        'utf32_german2_ci' => 'utf32',
        'utf32_croatian_ci' => 'utf32',
        'utf32_unicode_520_ci' => 'utf32',
        'utf32_vietnamese_ci' => 'utf32',
        'binary' => 'binary',
        'geostd8_general_ci' => 'geostd8',
        'geostd8_bin' => 'geostd8',
        'cp932_japanese_ci' => 'cp932',
        'cp932_bin' => 'cp932',
        'eucjpms_japanese_ci' => 'eucjpms',
        'eucjpms_bin' => 'eucjpms',
        'gb18030_chinese_ci' => 'gb18030',
        'gb18030_bin' => 'gb18030',
        'gb18030_unicode_520_ci' => 'gb18030',
    ];

    private function __construct() {}

    public static function getInstance( string $env_file ) {
        if ( is_null(self::$instance) ) {
            self::$instance = new Database();
        }
        self::$instance->conn = Connection::getInstance( $env_file );

        if ( !is_a( self::$instance->conn, 'PDO' )) {
            throw new \Exception('Connection to database failed.');
        }

        return self::$instance;
    }

    /**
     * Execute an SQL statement and return the number of affected rows
     */
    public static function exec( $sql ): int
    {
        $conn = Connection::getInstance();
        if ( $conn ) {
            return $conn->exec( $sql );
        } else {
            throw new \Exception('Error on connecting to database.');
        }
    }
    
    /**
     * Executes an SQL statement, returning a result set as a PDOStatement object
     */
    public static function query( $sql ): PDOStatement
    {
        $conn = Connection::getInstance();
        if ( $conn ) {
            return $conn->query( $sql );
        } else {
            throw new \Exception('Error on connecting to database.');
        }
    }

    /**
     * Fetch extended error information associated with the last operation on the database handle
     */
    public static function getError(): array
    {
        $conn = Connection::getInstance();
        return $conn->errorInfo();
    }

    public static function databases()
    {
        $sql = "SHOW DATABASES;";
        $stmt = self::query( $sql );

        return $stmt->fetchAll(PDO::FETCH_COLUMN);        
    }

    public static function tables( $database )
    {
        $sql = "SHOW TABLES FROM " . $database . ";";
        $stmt = self::query( $sql );

        return $stmt->fetchAll();
    }

    public static function getCollates()
    {
        return self::$collates;
    }

    public static function getCollateGroup( $collate )
    {
        return self::$collates[$collate];
    }

    public static function dropDatabase( $database )
    {
        $sql = 'DROP DATABASE IF EXISTS ' . $database . ';';
        return self::exec($sql);
    }

    public static function createDatabase( $database, $collate = 'utf8_general_ci' )
    {
        $sql = 'CREATE DATABASE ' . $database . ' DEFAULT CHARACTER SET ' . self::getCollateGroup($collate) . ' DEFAULT COLLATE ' . $collate . ';';
        return self::exec($sql);
    }

    public static function changeDatabase( $database )
    {
        $sql = "USE " . $database . ";";
        return self::exec( $sql );
    }

    public static function dropTable( $table )
    {
        $sql = 'DROP TABLE IF EXISTS ' . $table . ';';
        return self::exec($sql);
    }

}