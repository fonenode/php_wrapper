<pre><?php
require 'fonenode.php';
$fonenode = new fonenode('__auth_id___', '__auth_key___');

// Quick call
// print_r($fonenode->quick_call('23480___', 'Hey'));
// Call (with response id)
// print_r($fonenode->call('23470____', '___response_id___'));
// List calls
// print_r($fonenode->list_calls());
// Get a call
// print_r($fonenode->get_call('__id__'));

// List responses
// print_r($fonenode->list_responses());
// Get response
// print_r($fonenode->get_response('__id__'));
// delete response
// print_r($fonenode->delete_response('__id__'));

// List bills
// print_r($fonenode->list_bills());

echo '</pre>
		Status code: '.$fonenode->getStatusCode().'<br />';
?>