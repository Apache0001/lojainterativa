<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
    <link rel="stylesheet" href="<?= asset('/css/style.css') ?>">
</head>
<body>

<!-- main -->
<main>
    <form class='formcadastrar'action="<?= route('/cadastrar'); ?>" method='post'>
        <div class='grid-left'>
            <head>
                <h1>FABRICANTE E CATEGORIA</h1>
            </head>
            
                <label for=""><span>FABRICANTE</span> 
                    <input type="text" name='fabricante' placeholder="Digite o nome do Fabricante">
                </label>

                <label for=""><span>CATEGORIA</span>
                    <input type="text" name='categoria_1' placeholder="Digite o nome do Categoria 1">
                    <input type="text" name='categoria_2' placeholder="Digite o nome do Categoria 2">
                    <input type="text" name='categoria_3' placeholder="Digite o nome do Categoria 3">
                </label>
                
                <button class='btn-enviar' type='submit'>Adicionar</button>

        </div>
        <div class='grid-right'>
            <head>
                <h1>Produtos</h1>
            </head>
                <label for=""><span>NOME DO PRODUTO</span>
                    <input type="text" name='nome_produto' placeholder="Digite o nome do Produto">
                    <select name='fabricante_id' placeholder="Selecione o Fabricante">
                        <?php foreach($fabricantes as $fabricante) : ?>
                            <option value="<?= $fabricante->id?>"><?= $fabricante->nome?></option>
                        <?php endforeach ;?>
                    </select>
                    <select name='categoria_id'>
                    <?php foreach($categorias as $categoria) : ?>
                            <option value="<?= $categoria->id?>"><?= $categoria->nome?></option>
                        <?php endforeach ;?>
                    </select>
                    <input type="text" name='preco' placeholder="Digite o preço">
                </label>
        </div>
    </form>
</main>

    <form action="" method='post'>
        <div class='busca'></div>
        <div class='tabela'>
            <table>
                <tr>
                    <td>ID</td>
                    <td>Nome Produto</td>
                    <td>Fabricante</td>
                    <td>Categoria</td>
                    <td>Preço</td>
                    <td></td>
                    <td></td>
                </tr>
                <?php foreach($produtos as $produto) : ?>
                    <tr>
                        <td><?= $produto->id ?></td>
                        <td><?= $produto->nome ?></td>
                        <td><?= fabricante($produto->fabricante_id)?></td>
                        <td><?= categoria($produto->categoria_id)?></td>
                        <td><?= $produto->preco ?></td>
                        <td><input type="text" name='<?= $produto_id?>'><button>Deletar</button></td>
                    </tr>
                <?php endforeach ;?>

            </table>
        </div>
    </form>

    <!-- footer -->
    <footer>

    </footer>
    <!-- javascript global -->
    <script src="<?= asset('js/jquery.js') ?>"></script>
    <script src="<?= asset('js/form.js') ?>"></script>

</body>
</html>