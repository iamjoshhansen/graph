<?php

$CONST = array();
$CONST['DATA'] = './data/';
$CONST['DATA-V'] = $CONST['DATA'] . 'vertices/';
$CONST['DATA-E'] = $CONST['DATA'] . 'edges/';

$STATIC = array();
$STATIC['i-v'] = $CONST['DATA'] . 'i-v.txt';
$STATIC['i-e'] = $CONST['DATA'] . 'i-e.txt';





/*	Graph
------------------------------------------*/

	function initGraph () {
		global $STATIC, $CONST;

		mkdir($CONST['DATA']);
		mkdir($CONST['DATA-V']);
		mkdir($CONST['DATA-E']);

		file_put_contents($STATIC['i-v'], '0');
		file_put_contents($STATIC['i-e'], '0');
	}


	function destroyGraph () {
		global $CONST;
		if (is_dir($CONST['DATA'])) {
			delTree($CONST['DATA']);
		}
	}


	function delTree($dir) {
		$files = array_diff(scandir($dir), array('.','..'));
		foreach ($files as $file) {
			(is_dir("$dir/$file")) ? delTree("$dir/$file") : unlink("$dir/$file");
		}
		return rmdir($dir);
	}


	function pokeVertexI () {
		global $STATIC;
		$path = $STATIC['i-v'];
		$i = (int) file_get_contents($path);
		file_put_contents($path, ++$i);
		return $i;
	}


	function pokeEdgeI () {
		global $STATIC;
		$path = $STATIC['i-e'];
		$i = (int) file_get_contents($path);
		file_put_contents($path, ++$i);
		return $i;
	}










/*	Vertex
------------------------------------------*/

	function addVertex ($id, $properties) {

		global $CONST;

		$path = $CONST['DATA-V'] . $id . '/';

		if ( ! isset($id)) {
			$id = pokeVertexI();
		}

		if ( ! isset($properties)) {
			$properties = array();
		}

		if (is_dir($path)) {
			return false;
		} else {
			mkdir($path);
			file_put_contents($path . 'p.json', json_encode($properties));

			mkdir($path . 'ins');
			mkdir($path . 'outs');
			return true;
		}
	}





	function getVertexPropertyPath ($id) {
		global $CONST;
		return $CONST['DATA-V'] . $id . '/p.json';
	}




	function getVertexProperties ($id) {
		$path = getVertexPropertyPath($id);
		$is_vertex = is_file($path);

		if ($is_vertex) {
			$json = file_get_contents($path);
			$data = json_decode($json, true);
			return $data;
		} else {
			return null;
		}
	}




	function getVertexProperty ($id, $key) {
		$data = getVertexProperties($id);
		if ($data === null) {
			return null;
		} else {
			return $data[$key];
		}
	}


	function getVertexPropertyKeys ($id) {
		$data = getVertexProperties($id);
		if ($data === null) {
			return null;
		} else {
			return array_keys($data);
		}
	}



	function setVertexProperty ($id, $key, $val) {
		$data = getVertexProperties($id);
		if ($data === null) {
			return null;
		} else {
			$data[$key] = $val;

			$path = getVertexPropertyPath($id);
			return file_put_contents($path, json_encode($data));
		}
	}


	function setVertexProperties ($id, $properties) {
		$data = getVertexProperties($id);
		if ($data === null) {
			return null;
		} else {
			$data = array_merge($data, $properties);
			$path = getVertexPropertyPath($id);
			return file_put_contents($path, json_encode($data));
		}
	}


	function removeVertexProperties ($id, $keys) {
		$data = getVertexProperties($id);
		if ($data === null) {
			return null;
		} else {
			foreach ($keys as $key => $val) {
				unset($data[$key]);
			}
			$path = getVertexPropertyPath($id);
			return file_put_contents($path, json_encode($data));
		}
	}


	function removeVertexProperty ($id, $key) {
		return removeVertexProperties($id, [$key]);
	}









/*	Edge
------------------------------------------*/

	function addEdge ($id, $label, $source, $target, $properties) {

		global $CONST;

		if ( ! $id) {
			$id = pokeEdgeI();
		}

		if ( ! $properties) {
			$properties = array();
		}

		$edge_path = $CONST['DATA-E'] . $id . '/';
		$source_path = $CONST['DATA-V'] . $source . '/';
		$target_path = $CONST['DATA-V'] . $target . '/';


		if (is_dir($edge_path) && ! is_dir($source_path) && ! is_dir($target_path)) {
			return false;
		} else {

			mkdir($edge_path);


			/*	Required Properties
			------------------------------------------*/
				$required = array();
				$required['label'] = $label;
				$required['source'] = $source;
				$required['target'] = $target;

				file_put_contents($edge_path . 'e.json', json_encode($required));


			/*	Other Properties
			------------------------------------------*/
				file_put_contents($edge_path . 'p.json', json_encode($properties));


			/*	Update source vertex
			------------------------------------------*/
				$source_file = $source_path . 'outs/' . $label . '.json';

				$source_data = array();
				if (is_file($source_file)) {
					$source_data = json_decode(file_get_contents($source_file), true);
				}

				array_push($source_data, $id);

				file_put_contents($source_file, json_encode($source_data));


			/*	Update target vertex
			------------------------------------------*/
				$target_file = $target_path . 'ins/' . $label . '.json';

				$target_data = array();
				if (is_file($target_file)) {
					$target_data = json_decode(file_get_contents($target_file), true);
				}

				array_push($target_data, $id);

				file_put_contents($target_file, json_encode($target_data));

			return true;
		}
	}


	function getEdgePropertyPath ($id) {
		global $CONST;
		return $CONST['DATA-E'] . $id . '/p.json';
	}


	function getEdgeDataPath ($id) {
		global $CONST;
		return $CONST['DATA-E'] . $id . '/e.json';
	}


	function getEdgeData ($id) {
		$path = getEdgeDataPath($id);
		$is_vertex = is_file($path);

		if ($is_vertex) {
			$json = file_get_contents($path);
			$data = json_decode($json, true);
			return $data;
		} else {
			return null;
		}
	}


	function getEdgeLabel ($id) {
		return getEdgeData($id)['label'];
	}


	function getEdgeProperties ($id) {
		$path = getEdgePropertyPath($id);
		$is_Edge = is_file($path);

		if ($is_Edge) {
			$json = file_get_contents($path);
			$data = json_decode($json, true);
			return $data;
		} else {
			return null;
		}
	}




	function getEdgeProperty ($id, $key) {
		$data = getEdgeProperties($id);
		if ($data === null) {
			return null;
		} else {
			return $data[$key];
		}
	}


	function setEdgeProperty ($id, $key, $val) {
		$data = getEdgeProperties($id);
		if ($data === null) {
			return null;
		} else {
			$data[$key] = $val;

			$path = getEdgePropertyPath($id);
			return file_put_contents($path, json_encode($data));
		}
	}


	function setEdgeProperties ($id, $properties) {
		$data = getEdgeProperties($id);
		if ($data === null) {
			return null;
		} else {
			$data = array_merge($data, $properties);
			$path = getEdgePropertyPath($id);
			return file_put_contents($path, json_encode($data));
		}
	}

	function getEdgePropertyKeys ($id) {
		$data = getEdgeProperties($id);
		if ($data === null) {
			return null;
		} else {
			return array_keys($data);
		}
	}

	function setEdgeLabel ($id, $label) {
		$data = getEdgeData($id);
		if ($data === null) {
			return null;
		}

		$data['label'] = $label;
		$path = getEdgeDataPath($id);
		return file_put_contents($path, json_encode($data));
	}


	function removeEdgeProperties ($id, $keys) {
		$data = getEdgeProperties($id);
		if ($data === null) {
			return null;
		} else {
			foreach ($keys as $key => $val) {
				unset($data[$key]);
			}
			$path = getEdgePropertyPath($id);
			return file_put_contents($path, json_encode($data));
		}
	}


	function removeEdgeProperty ($id, $key) {
		return removeEdgeProperties($id, [$key]);
	}




?>
