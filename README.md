# ------------------------- CRIE O AMBIENTE ------------------------
    # --- SUBA O DOCKER ---
    docker-compose up -d

    # --- CRIE O BANCO DE DADOS ---
    docker exec -it rotacao_de_pastagem_para_gado_db_1 mysql -uroot -proot
    CREATE DATABASE rotacao_pastagem;

    # --- SUBA O SERVIDOR ---
    Saia do mysql (exit) e migre as tabelas:
    php artisan migrate

    # --- SUBA O SERVIDOR ---
    php artisan serve


# ------------------------- DOCUMENTAÇÃO ------------------------
    # --- EXEMPLOS DE CONFIGURAÇÕES DE ENTRADA
    - Adicione primeiro as pastagens. O sistema faz um select nas pastagens existentes, caso não houver nenhuma não será possivel a escolha e, logo, nem o salvamento.
    
    -E melhor utilisar numeros pares para a verificação da rotaçao

    - PASTAGEM:
    capacidade_suporte: 1 | quantidade_forragem: 50 | dias_recuperacao: 5 

    capacidade_suporte: 2 | quantidade_forragem: 30 | dias_recuperacao: 3

    capacidade_suporte: 3 | quantidade_forragem: 80 | dias_recuperacao: 8
    
    capacidade_suporte: 4 | quantidade_forragem: 20 | dias_recuperacao: 2

    - ANIMAL:
    peso: 500 | idade: 20 | necessidade_nutricional: 20 | pastagem_atual: 3

    peso: 250 | idade: 7 | necessidade_nutricional: 6 | pastagem_atual: 1

    peso: 220 | idade: 8 | necessidade_nutricional: 6 | pastagem_atual: 1
    
    peso: 400 | idade: 13 | necessidade_nutricional: 15 | pastagem_atual: 2


# ------------------------- LOGIGA DO PROGRAMA ------------------------
    - Para melhor mapeamento da rotaçao o objeto plano_rotacao foi criado. Ele guarda as informaçoes de: dia, pastagem_id, animais (animal_id que estão no pasto), qtd_animal (contagem de animais no pasto) e forragem_disponivel (degradaçao do pasto em %).

    - Mais uma parametro foi atribuido ao obejto Pastagem: forragem_disponivel (quantidade disponivel de forragem por dia)


    Considera-se que o usuário tente alocar os animais da melhor forma, mas nem sempre é possivel a permanencia do animal em determinada pastagem. Para isso, verifica-se no dia 1 onde os animais estavam no início (dia 0) e é feita as seguintes perguntas:
    1- A forragem disponivel da pastagem NÃO é o suficiente para os animais que estao nelas? (forragem_disponivel < necessidade_nutrcional)
    2- A pastagem suporta NÃO suporta numero de animais? (capacidade_suporte < qtd_animal)

    Se em algum dos casos a resposta for NÃO o código atribuirá true. Isso significa que eu devo rotacionar os animais.
    Enquanto as perguntas forem true, o programa prossegue em verificar o dia e a pastagem com problema e retira algum animal. Se for a pergunta 1, o programa retira o animal com maior necessidade_nutrcional, visto que o pasto não possui forragem suficiente. Já se for a pergunta 2, ele retira o animal com menor necessidade_nutrcional, pois o problema está na quantidade de animais no pasto. 
    
    Depois, caso haja animais sem pastagem, o programa procura uma adequada pra ele. No objeto pastagem temos a forragem_disponivel naquele dia, assim as pastagens são organizadas por ordem daquela q possui maior quantidade de forragem_disponivel. E verificado se já tem animais lá e quantos podem ficar. Se estiver de acordo, o animal e realocado, caso contrario outra pastagem é procurada.
    A verificaçao da qualidade do pasto e a forragem disponível e revisada toda a vez que um plano de rotçao é inserido ou modificado.
    
    Por fim, animal removido é salvo em um array. Caso a pastagem fique sem animais, ela começa a se recuperar. Para isso e feito uma conta: 
    $recuperaçao_por_dia = quantidade_forragem/dias_recuperacao
    $pastagem->forragem_disponivel += $recuperaçao_por_dia
    A forragem_disponivel e salva em pastagem. 
    A recuperaçao de forragem salva em formato de porcentagem no objeto plano_rotaçao.

    Esse sistema vai acontecer toda a vez que passar um dia até o final e mostrar na tela o plano de rotação sugerido.
