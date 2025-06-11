<?php
class Conexao {
    public static function conectar() {
        return new mysqli("localhost", "root", "", "fastfood");
    }
}
