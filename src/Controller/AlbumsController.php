<?php

namespace App\Controller;

use Psr\Http\Message\ResponseInterface as Response;
use Psr\Http\Message\ServerRequestInterface as Request;
use Slim\Exception\HttpNotFoundException;

class AlbumsController extends Controller
{
	public function default(Request $request, Response $response)
	{
		$albums = json_decode(file_get_contents(__DIR__.'/../../data/albums.json'), true);
    	return $this->render($response, 'default.html', ['albums' => $albums]);
	}

	public function search(Request $request, Response $response)
	{
		$albums = json_decode(file_get_contents(__DIR__.'/../../data/albums.json'), true);

		$query = $request->getQueryParam('q');

		
		if($query){
			$albums = array_values(array_filter($albums, function($album) use ($query){
				return strpos($album['title'], $query) !== false or strpos($album['artist'], $query) !== false;
			}));
		}
		

    	return $this->render($response, 'search.html', [
    		'query' => $query, 
    		'albums' => $albums]);
	}

	public function form(Request $request, Response $response)
	{
		$albums = json_decode(file_get_contents(__DIR__.'/../../data/albums.json'), true);

		$query = $request->getParam('q');

		
		if($request->isPost()){
			$albums = array_values(array_filter($albums, function($album) use ($query){
				return strpos($album['title'], $query) !== false or strpos($album['artist'], $query) !== false;
			}));
		}
		

    	return $this->render($response, 'form.html', [
    		'query' => $query, 
    		'albums' => $albums]);
	}
	

	public function details(Request $request, Response $response, $args = [])
	{
		$albums = json_decode(file_get_contents(__DIR__.'/../../data/albums.json'), true);		
		$key = array_search($args['id'], array_column($albums, 'id'));

		if($key === false){
			throw new HttpNotFoundException($request, $response);
		}
		
    	return $this->render($response, 'details.html', [
    		'album' => $albums[$key]]);
	}
}
