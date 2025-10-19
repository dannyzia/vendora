<div>
    <h1>bKash Settings</h1>

    <form action="" method="POST">
        <div>
            <label for="app_key">App Key</label>
            <input type="text" name="app_key" id="app_key" value="{{ $settings['app_key'] ?? '' }}">
        </div>
        <div>
            <label for="app_secret">App Secret</label>
            <input type="text" name="app_secret" id="app_secret" value="{{ $settings['app_secret'] ?? '' }}">
        </div>
        <div>
            <label for="username">Username</label>
            <input type="text" name="username" id="username" value="{{ $settings['username'] ?? '' }}">
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" id="password" value="{{ $settings['password'] ?? '' }}">
        </div>
        <button type="submit">Save</button>
    </form>
</div>
