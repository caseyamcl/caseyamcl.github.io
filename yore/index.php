<?php


use Symfony\Component\HttpFoundation\Request;

require_once(__DIR__ . '/vendor/autoload.php');

$request = Request::createFromGlobals();

// Prepare articles..
$articles = prepareArticles(
    __DIR__ . '/pages',
    isset($_GET['skip-cache']) ? null : __DIR__ . '/.pageindex'
);

$activeArticleSlug = trim($request->getPathInfo(), '/') ?: key($articles);

/**
 * @param string $path
 * @param string|null $cacheFilePath
 * @return mixed
 */
function prepareArticles(string $path, string $cacheFilePath = null)
{
    $articles = [];

    if ($cacheFilePath && is_readable($cacheFilePath)) {
        return unserialize(file_get_contents($cacheFilePath));
    }
    else {

        $parseDown = new Parsedown();
        $frontMatter = new Webuni\FrontMatter\FrontMatter();

        foreach (glob($path . '/*.html') as $file) {

            $doc = $frontMatter->parse("---\n" . file_get_contents($file));
            $slug = basename($file, '.' . pathinfo($file, PATHINFO_EXTENSION));
            $articles[$slug] = $doc->getDataWithContent('content');

            if (isset($articles[$slug]['timestamp'])) {
                $articles[$slug]['timestamp'] = \DateTime::createFromFormat(
                    'Y-m-d H:i:s',
                    $articles[$slug]['timestamp']
                );
            }

            if (isset($articles[$slug]['content'])) {
                $articles[$slug]['content'] = $parseDown->text($articles[$slug]['content']);
            }
        }

        uasort($articles, function($a, $b) {
            if (isset($a['timestamp']) && isset($b['timestamp'])) {
                if ($a['timestamp'] == $b['timestamp']) {
                    return 0;
                }
                else {
                    return $a['timestamp'] > $b['timestamp'] ? 1 : -1;
                }
            }
            elseif (isset($a['timestamp'])) {
                return -1;
            }
            elseif (isset($b['timestamp'])) {
                return 1;
            }
            else {
                return 0;
            }
        });

        return $articles;
    }
}

$activeArticle = (isset($articles[$activeArticleSlug])) ? $articles[$activeArticleSlug] : null;
if (! $activeArticle) {
 http_response_code(404);
}

if (in_array('application/json', $request->getAcceptableContentTypes())):
    echo json_encode(array_merge($activeArticle, ['slug' => $activeArticleSlug]) ?: ['message' => 'Article not found']);
else:
?>
<!DOCTYPE html>
<html lang="en-US">

<head>
    <meta charset="utf-8">
    <meta name=viewport content="width=device-width, initial-scale=1">
    <meta http-equiv="x-ua-compatible" content="ie=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <meta name="description" content="Personal website of Casey McLaughlin">
    <meta name="author" content="Casey McLaughlin">
    <meta name="keywords" content="Developer, Systems Specialist, HPC, Instructor, Tallahassee">

    <!-- Google Schema.org Markup -->
    <meta itemprop="name" content="Casey McLaughlin">
    <meta itemprop="description" content="Personal website of Casey McLaughlin">
    <meta itemprop="image" content="https://caseymclaughlin.com/headshot.jpg">

    <!-- Open Graph data -->
    <meta property="og:title" content="Casey McLaughlin" />
    <meta property="og:type" content="website" />
    <meta property="og:description" content="Personal website of Casey McLaughlin" />
    <meta property="og:locale" content="en_US" />
    <meta property="og:url" content="https://caseymclaughlin.com/" />
    <meta property="og:site_name" content="Casey McLaughlin" />

    <title>Casey McLaughlin: Personal Website</title>

    <link href="https://fonts.googleapis.com/css?family=Archivo+Black|Arimo" rel="stylesheet">
    <link href="assets/style.css" rel="stylesheet" type="text/css">
</head>

<body>

    <nav id="yore-article-browser" role="navigation">

        <ul class="article-list">
            <?php foreach ($articles as $slug => $article): ?>
                <li <?php if ($slug == $activeArticleSlug) { echo "class='active'"; } ?> >
                    <a href="<?php echo $request->getBasePath() . '/' . $slug; ?>"><?php echo $article['title']; ?></a>
                    <span><?php echo $article['timestamp']->format('Y-m-d'); ?></span>
                </li>
            <?php endforeach; ?>
        </ul>

    </nav>

    <div id="yore-article-viewer">

        <article role="main">

            <button class="article-browser-toggle">&larr; Menu</button>

            <?php if ($activeArticle): ?>
                <header>
                    <h1 id="active-article-title"><?php echo $activeArticle['title']; ?></h1>
                    <p class="article-meta">
                        <time datetime="<?php  ?>" id="active-article-datetime">
                            <?php echo $activeArticle['timestamp']->format('Y-m-d'); ?>
                        </time>
                        &mdash; Casey McLaughlin
                    </p>
                </header>

                <div class="article-body" id="active-article-body">
                    <?php echo $activeArticle['content']; ?>
                </div>

                <footer>
                    <button class="article-browser-toggle">&larr; Menu</button>
                </footer>

            <?php else: ?>
                <h1>404 - Article not Found</h1>
                <p>Whoops!  You ended up nowhere.  You probably want to go somewhere instead.</p>
            <?php endif; ?>

        </article>

        <footer style="text-align: center; margin-top: 2.5em; font-size: 0.7em; padding: 0 0.5em 0.5em 0.5em">
            This work is licensed under a<br/><a rel="license" href="http://creativecommons.org/licenses/by-nc-nd/4.0/">Creative Commons Attribution-NonCommercial-NoDerivatives 4.0 International License</a>.
        </footer>
    </div>

    <script
            src="https://code.jquery.com/jquery-2.2.4.min.js"
            integrity="sha56-BbhdlvQf/xTY9gja0Dq3HiwQF8LaCRTXxZKRutelT44="
            crossorigin="anonymous"></script>
    <script src="assets/scripts.js"></script>
</body>

</html>
<?php endif; ?>
