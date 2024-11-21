add_action('rest_api_init', function () {
    register_rest_route('desc-studio/v1', '/users', [
        'methods' => 'POST',
        'callback' => 'create_user',
    ]);
    register_rest_route('desc-studio/v1', '/users/(?P<id>\d+)', [
        'methods' => 'GET',
        'callback' => 'get_user',
    ]);
    register_rest_route('desc-studio/v1', '/users/(?P<id>\d+)', [
        'methods' => 'PUT',
        'callback' => 'update_user',
    ]);
    register_rest_route('desc-studio/v1', '/users/(?P<id>\d+)', [
        'methods' => 'DELETE',
        'callback' => 'delete_user',
    ]);
});

function create_user($request) {
    $params = $request->get_params();
    $user_id = wp_create_user($params['username'], $params['password'], $params['email']);
    if (is_wp_error($user_id)) return $user_id;
    update_user_meta($user_id, 'bio', $params['bio']);
    update_user_meta($user_id, 'phone', $params['phone']);
    return rest_ensure_response(['user_id' => $user_id]);
}

function get_user($request) {
    $user_id = $request['id'];
    $user = get_userdata($user_id);
    if (!$user) return new WP_Error('user_not_found', 'User not found', ['status' => 404]);
    return rest_ensure_response([
        'id' => $user_id,
        'username' => $user->user_login,
        'email' => $user->user_email,
        'bio' => get_user_meta($user_id, 'bio', true),
        'phone' => get_user_meta($user_id, 'phone', true),
    ]);
}

function update_user($request) {
    $user_id = $request['id'];
    $params = $request->get_params();
    if (!get_userdata($user_id)) return new WP_Error('user_not_found', 'User not found', ['status' => 404]);
    wp_update_user([
        'ID' => $user_id,
        'user_email' => $params['email']
    ]);
    update_user_meta($user_id, 'bio', $params['bio']);
    update_user_meta($user_id, 'phone', $params['phone']);
    return rest_ensure_response(['message' => 'User updated']);
}

function delete_user($request) {
    $user_id = $request['id'];
    if (!get_userdata($user_id)) return new WP_Error('user_not_found', 'User not found', ['status' => 404]);
    wp_delete_user($user_id);
    return rest_ensure_response(['message' => 'User deleted']);
}
