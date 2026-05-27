<?php

class Connection
{
    private static ?PDO $connection = null;

    public static function getConnection(): ?PDO
    {
        if (self::$connection == null) {
            $opcoes = array(
                //Define o charset da conexão
                PDO::MYSQL_ATTR_INIT_COMMAND => "SET NAMES utf8",
                //Define o tipo do erro como exceção
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                //Define o tipo do retorno das consultas
                PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC
            );

            self::$connection =
                new PDO(
                    "mysql:host=localhost;dbname=bancodecarros",
                    "root",
                    "",
                    $opcoes
                );
        }

        return self::$connection;
    }
}
