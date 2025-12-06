<?php
require_once __DIR__ . '/../Database.php';

$db = new Database();
$conn = $db->getConnection();

$sql = "
    INSERT INTO item (nome, valor, descricao) VALUES
    ('Água mineral (500ml)', 5.00, 'Garrafa 500ml'),
    ('Refrigerante lata', 8.50, 'Refrigerante 350ml'),
    ('Cerveja lata', 10.00, 'Cerveja 350ml'),
    ('Café expresso', 6.00, 'Café passado'),
    ('Sanduíche natural', 18.00, 'Sanduíche com frios e salada'),
    ('Suco natural', 12.00, 'Suco de frutas 300ml'),
    ('Sobremesa', 15.00, 'Torta ou pudim (variável)'),
    ('Serviço de Lavanderia (peça)', 25.00, 'Lavagem por peça'),
    ('Snack (mix)', 7.50, 'Mix de snacks do minibar'),
    ('Água com gás (500ml)', 6.00, 'Garrafa 500ml')
;

";

$conn->exec($sql);

echo "Tabela item povoada com sucesso.\n";

?>
