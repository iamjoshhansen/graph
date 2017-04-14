<?php

header('Content-Type: text/plain');

include('./graph-fn.php');

destroyGraph();
initGraph();

echo addVertex('josh', json_decode('{"name":"Joshua Hansen","age":29}')) ? 'vertex added: josh' : 'vertex add failed';
echo "\n";

echo addVertex('charlie', json_decode('{"name":"Christi Hansen","age":28}')) ? 'vertex added: charlie' : 'vertex add failed';
echo "\n";

echo addEdge(null, 'wife', "josh", "charlie", json_decode('{"date":"Oct 1, 2011"}')) ? 'edge added: wife' : 'edge add failed';
echo "\n";

echo addEdge(null, 'husband', "charlie", "josh", json_decode('{"date":"Oct 1, 2011"}')) ? 'edge added: husband' : 'edge add failed';
echo "\n";

echo addVertex('steve', json_decode('{"name":"Steve Black"}')) ? 'vertex added: steve' : 'vertex add failed';
echo "\n";

echo addEdge(null, 'friend', "josh", "steve", null) ? 'edge added: friend' : 'edge add failed';
echo "\n";

echo addEdge(null, 'friend', "charlie", "steve", null) ? 'edge added: friend' : 'edge add failed';
echo "\n";

echo addEdge(null, 'friend', "steve", "josh", null) ? 'edge added: friend' : 'edge add failed';
echo "\n";

echo addEdge(null, 'friend', "steve", "charlie", null) ? 'edge added: friend' : 'edge add failed';
echo "\n";

echo "\n------------------------------------------\n\n";

echo(json_encode(getEdgeProperties('1')));
json_encode(setEdgeProperty('1', 'date', '2011-10-01'));
echo(json_encode(getEdgeProperties('1')));

?>
