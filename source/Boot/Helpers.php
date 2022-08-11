<?php

###############
###   URL   ###
###############
/**
 * @param string|null $path
 * @return string
 */
function url(string $path = null): string
{
    if (strpos($_SERVER['HTTP_HOST'], "localhost")){
        if($path){
            return CONF_URL_TEST . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return CONF_URL_TEST;
    }
    if($path){
        return CONF_URL_BASE . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }
    return CONF_URL_BASE;
    
}

function route(string $path = null): string
{
    if (strpos($_SERVER['HTTP_HOST'], "localhost")){
        if($path){
            return CONF_URL_TEST . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return CONF_URL_TEST;
    }
    if($path){
        return CONF_URL_BASE . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }
    return CONF_URL_BASE;
    
}

################
### PASSWORD ###
################

function is_passwd(string $password): bool
{
    if (password_get_info($password)['algo'] || mb_strlen($password) >= CONF_PASSWD_MIN_LEN && mb_strlen($password) <= CONF_PASSWD_MAX_LEN ? true : false ) {
        return true;
    }

    return false;
}

function validPasswd(string $password){
    if(strlen($password) < 8){
        return false;
    }
    return true;
}

//gera uma hash com a senha do usuário
function passwd(string $password)
{
    if(!empty(password_get_info($password)['algo'])){
        return $password;
    }
    return password_hash($password, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}

//verifica se o password enviado pelo usuário corresponde ao no banco
function passwd_verify(string $password, string $hash): bool
{
    return password_verify($password, $hash);
}

//atualiza uma nova rash no banco
function passwd_rehash(string $hash): bool
{
    return password_needs_rehash($hash, CONF_PASSWD_ALGO, CONF_PASSWD_OPTION);
}

################
### REQUESTS ###
################

//cria um csrf_input para proteger o envio de formulário.
function csrf_input(): string
{
    $session = new \Source\Core\Session();
    $session->csrf();
    return "<input type='hidden' name = 'csrf' value='". ($session->csrf_token ?? "") ."'/>";
}

//verifica a confiabilidade do csrf_input
function csrf_verify($request):bool
{
    $session = new \Source\core\Session();
    if(empty($session->csrf_token) || empty($request['csrf']) || $request['csrf'] != $session->csrf_token){
        return false;
    }
    return true;
}



############
### DATE ###
############

/**
 * @param string|null $date
 * @param string $format
 * @return string
 * @throws Exception
 */
function date_fmt(?string $date = null, string $format = "d/m/Y H\hi"): string
{
    $date = (!empty($date) ? 'now' : $date);
    return (new DateTime($date))->format($format);
}

/**
 * @param string $date
 * @return string
 */
function date_fmt_br(string $date): string
{
    $date = (!empty($date) ? 'now' : $date);
    return (new DateTime($date))->format(CONF_DATE_BR);
}

/**
 * @param string $date
 * @return string
 */
function date_fmt_app(?string $date = null): string
{
    $date = (!empty($date) ? 'now' : $date);
    return (new DateTime($date))->format(CONF_DATE_APP);
}



function asset(string $path = null, $theme = CONF_VIEW_THEME): string
{
    if (strpos($_SERVER['HTTP_HOST'], "localhost")){
        if($path){
            return CONF_URL_TEST . "/themes/{$theme}/assets/". "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
        }
        return CONF_URL_TEST. "/themes/{$theme}/assets/";
    }
    if($path){
        return CONF_URL_BASE . "/themes/{$theme}/assets" . "/" . ($path[0] == "/" ? mb_substr($path, 1) : $path);
    }
    return CONF_URL_BASE. "/themes/{$theme}/assets";
}

function fabricante(int $id){
    $fabricante = (new \Source\Models\Fabricante)->findById($id);
    return $fabricante->nome;
}

function categoria(int $id){
    $categoria = (new \Source\Models\Categoria)->findById($id);
    return $categoria->nome;
}
