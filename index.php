<?php
require_once 'database/database.php';
require_once 'model/Quarto.php';
require_once 'model/Reserva.php';
require_once 'model/Func.php';

try {
    // Establish database connection
    $database = new Database();
    $conn = $database->getConnection();
    
    // Example: List all rooms
    $quarto = new Quarto($conn);
    $quartos = $quarto->lerTodos();
    
    echo "Sistema de Gerenciamento de Hotel\n";
    echo "==================================\n\n";
    
    if ($quartos) {
        echo "Quartos disponíveis:\n";
        foreach ($quartos as $q) {
            echo "ID: " . $q['id'] . " | Número: " . $q['numero'] . " | Status: " . $q['status'] . "\n";
        }
    } else {
        echo "Nenhum quarto cadastrado.\n";
    }
    
} catch (Exception $e) {
    echo "Erro: " . $e->getMessage();
}
?>