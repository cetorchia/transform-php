<?php
/**
 * Transform PHP (c) 2016 Carlos Torchia
 * This program is licensed under the GNU GENERAL PUBLIC LICENSE.
 * NO WARRANTY.
 */

ini_set('display_errors', 1);
error_reporting(E_ALL);

require_once('geturl.php');

class BadRequestException extends Exception
{
}

function main()
{
    try {
        $operation = get_operation();
        if ($operation === 'transform') {
            $parameters = get_parameters();
            validate_parameters($parameters);
            $transformation = $parameters['transformation'];
            $url = $parameters['url'];
            $data = geturl($url);
            $transformed_data = transform($transformation, $data);
            echo $transformed_data;
        } else {
            $html = get_html();
            echo $html;
        }
    } catch (BadRequestException $e) {
        header('status: 400');
        echo $e->getMessage();
    } catch (Exception $e) {
        header('status: 500');
    }
}

function get_html()
{
    return file_get_contents('app.html');
}

function get_operation()
{
    if (isset($_GET['transformation'])) {
        return 'transform';
    } else {
        return null;
    }
}

function get_parameters()
{
    $encoded_transformation = $_GET['transformation'];
    $encoded_url = $_GET['url'];
    $url = base64_decode($encoded_url);
    $transformation = json_decode(base64_decode($encoded_transformation), true);
    return array('transformation' => $transformation,
                 'url' => $url);
}

function validate_parameters($parameters)
{
    if (!$parameters['transformation']) {
        throw new BadRequestException('Missing transformation');
    } else if (!$parameters['url']) {
        throw new BadRequestException('Missing url');
    }
}

function transform($transformation, $data)
{
    if (is_search_and_replace($transformation)) {
        return transform_search_and_replace($transformation, $data);
    } else {
        throw new BadRequestException('Invalid transformation');
    }
}

function is_search_and_replace($transformation)
{
    if (!isset($transformation['regex']) || !$transformation['regex']) {
        return false;
    } else if (!isset($transformation['replace']) || !$transformation['replace']) {
        return false;
    } else {
        return true;
    }
}

function transform_search_and_replace($transformation, $data)
{
    return preg_replace('/' . $transformation['regex'] . '/', $transformation['replace'], $data);
}

main();
