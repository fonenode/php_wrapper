<pre><?php
require 'fonenode.php';
$fonenode = new fonenode('__auth_id__', '__auth_secret__');

$data = array(
    'text' => 'Which is your favorite football team? Press one for Manchester United. Two for Chelsea. Three for Arsenal. Four for Liverpool.',
    'retries' => 3, // optional
    'no_input' => 'You did not make a selection', // optional
    'get_digits' => true, // We want to get input from user
    'wrong_choice_response' => 'Sorry, you have made a wrong selection.',
    // And here are choices for the user
    // A digit is mapped to a corresponding text to be read
    'choices' => array(
        array('digit' => 1, 'label' => 'Manchester United', 'text' => 'You selected Man United. Great!'),
        array('digit' => 2, 'label' => 'Chealsea', 'text' => 'You selected Chealsea. Wow!'),
        array('digit' => 3, 'label' => 'Arsenal', 'text' => 'You selected Arsenal. Up gunners!'),
        array('digit' => 4, 'label' => 'Liverpool', 'text' => 'You selected Liverpool. Beautiful!')
    )
);
print_r($fonenode->create_response($data));
echo '</pre>
		Status code: '.$fonenode->getStatusCode().'<br />';//*/
?>