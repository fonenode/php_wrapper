This is the PHP wrapper for the [Fonenode API](http://fonenode.com/docs). More methods coming soon.

##Init

	require 'path/to/fonenode.php';
	$fonenode = new fonenode('__auth_id___', '__auth_key___');

##Calls
1. quick_call(to, text, [voice], [from])
2. call(to, response_id, [voice], [from])
3. list_calls ([limit], [offset], [filter])
4. get_call (id)

##Responses
1. create_response (parameter_array)
2. list_responses ([limit], [offset])
3. get_response (id)
4. delete_response (id)

##Numbers
1. list_my_numbers ([limit], [offset])
3. update_number_response (number_id, respomse_id)

##Billing
1. list_bills ([limit], [offset], [filter])

See demo.php for usage examples. Also see fonenode.php to dig more.