<!DOCTYPE html>
<html lang="es">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Icono Virtual - Soluciones Inteligentes con IA</title>
    <link rel="stylesheet" href="css/style.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <link rel="icon" type="image/png"
        href="https://iconovirtual.com.co/wp-content/uploads/2024/05/cropped-favicon-270x270.png">
    <meta name="robots" content="index, follow">

    <?php
// Security Headers
header("X-Frame-Options: SAMEORIGIN");
header("X-Content-Type-Options: nosniff");
header("X-XSS-Protection: 1; mode=block");
header("Referrer-Policy: strict-origin-when-cross-origin");

// Load tracking configuration
$tracking = json_decode(file_get_contents('data/tracking.json'), true);

// Google Tag Manager - Head
if ($tracking['google_tag_manager']['enabled']) {
    echo "<!-- Google Tag Manager -->\n";
    echo "<script>(function (w, d, s, l, i) {
            w[l] = w[l] || []; w[l].push({
                'gtm.start': \n";
    echo "new Date().getTime(),event:'gtm.js'});var f=d.getElementsByTagName(s)[0],\n";
    echo "j=d.createElement(s),dl=l!='dataLayer'?'&l='+l:'';j.async=true;j.src=\n";
    echo "'https://www.googletagmanager.com/gtm.js?id='+i+dl;f.parentNode.insertBefore(j,f);\n";
    echo "})(window,document,'script','dataLayer','" . $tracking['google_tag_manager']['container_id'] . "');</script>\n";
    echo "<!-- End Google Tag Manager -->\n";
}

// Google Analytics
if ($tracking['google_analytics']['enabled']) {
    echo "<!-- Google Analytics -->\n";
    echo "<script async src='https://www.googletagmanager.com/gtag/js?id=" . $tracking['google_analytics']['tracking_id'] . "'></script>\n";
    echo "window.dataLayer = window.dataLayer || [];\n";
    echo "function gtag(){dataLayer.push(arguments);}\n";
    echo "gtag('js', new Date());\n";
    echo "gtag('config', '" . $tracking['google_analytics']['tracking_id'] . "');\n";
    echo "</script>\n";
    echo "<!-- End Google Analytics -->\n";
}

// Google Ads
if ($tracking['google_ads']['enabled']) {
    echo "<!-- Google Ads -->\n";
    // If Google Analytics is not enabled, we need to load gtag.js
    if (!$tracking['google_analytics']['enabled']) {
        echo "<script async src='https://www.googletagmanager.com/gtag/js?id=" . $tracking['google_ads']['conversion_id'] . "'></script>\n";
        echo "<script>\n";
        echo "window.dataLayer = window.dataLayer || [];\n";
        echo "function gtag(){dataLayer.push(arguments);}\n";
        echo "gtag('js', new Date());\n";
    }
    else {
        echo "<script>\n";
    }
    echo "gtag('config', '" . $tracking['google_ads']['conversion_id'] . "');\n";
    echo "</script>\n";
    echo "<!-- End Google Ads -->\n";
}


// Meta Pixel
if ($tracking['meta_pixel']['enabled']) {
    echo "<!-- Meta Pixel Code -->\n";
    echo "<script>\n";
    echo "!function(f,b,e,v,n,t,s)\n";
    echo "{if(f.fbq)return;n=f.fbq=function(){n.callMethod?\n";
    echo "n.callMethod.apply(n,arguments):n.queue.push(arguments)};\n";
    echo "if(!f._fbq)f._fbq=n;n.push=n;n.loaded=!0;n.version='2.0';\n";
    echo "n.queue=[];t=b.createElement(e);t.async=!0;\n";
    echo "t.src=v;s=b.getElementsByTagName(e)[0];\n";
    echo "s.parentNode.insertBefore(t,s)}(window, document,'script',\n";
    echo "'https://connect.facebook.net/en_US/fbevents.js');\n";
    echo "fbq('init', '" . $tracking['meta_pixel']['pixel_id'] . "');\n";
    echo "fbq('track', 'PageView');\n";
    echo "</script>\n";
    echo "<noscript><img height='1' width='1' style='display:none'\n";
    echo "src='https://www.facebook.com/tr?id=" . $tracking['meta_pixel']['pixel_id'] . "&ev=PageView&noscript=1'\n";
    echo "/></noscript>\n";
    echo "<!-- End Meta Pixel Code -->\n";
}
?>
</head>

<body>
    <?php
// Google Tag Manager - Body
if ($tracking['google_tag_manager']['enabled']) {
    echo "<!-- Google Tag Manager (noscript) -->\n";
    echo "<noscript><iframe src='https://www.googletagmanager.com/ns.html?id=" . $tracking['google_tag_manager']['container_id'] . "'\n";
    echo "height='0' width='0' style='display:none;visibility:hidden'></iframe></noscript>\n";
    echo "<!-- End Google Tag Manager (noscript) -->\n";
}

// Load content
$content = json_decode(file_get_contents('data/content.json'), true);
?>

    <!-- Header / Navigation -->
    <header class="main-header">
        <div class="container header-container">
            <div class="logo-container">
                <a href="#hero">
                    <img src="https://iconovirtual.com.co/wp-content/uploads/2023/12/logo-icono-virtual-blanco.png"
                        alt="Icono Virtual Logo" class="main-logo">
                </a>
            </div>
            <nav class="main-nav">
                <ul>
                    <li><a href="#hero" class="nav-link">Inicio</a></li>
                    <?php foreach ($content['services'] as $service): ?>
                    <li><a href="#service-<?php echo $service['id']; ?>" class="nav-link">
                            <?php echo str_replace('Agente IA ', '', $service['agent_name']); ?>
                        </a>
                    </li>
                    <?php
endforeach; ?>
                    <li><a href="#footer" class="nav-link">Contacto</a></li>
                </ul>
            </nav>
        </div>
    </header>

    <!-- Animated Background -->
    <div class="background-container">
        <div class="gradient-orb orb-1"></div>
        <div class="gradient-orb orb-2"></div>
        <div class="gradient-orb orb-3"></div>
        <div class="particles" id="particles"></div>
    </div>

    <!-- Hero Section -->
    <section class="hero" id="hero">
        <div class="container">
            <div class="hero-layout">
                <!-- Left: Text Content -->
                <div class="hero-text-side">
                    <h1 class="hero-title">
                        <?php echo $content['hero']['title']; ?>
                    </h1>
                    <p class="hero-subtitle">
                        <?php echo $content['hero']['subtitle']; ?>
                    </p>
                    <div class="hero-cta-container" style="margin-top: 2.5rem;">
                        <a href="#footer" class="btn-primary"
                            style="display: inline-block; width: auto; padding: 18px 45px;">
                            <?php echo $content['hero']['cta']; ?>
                        </a>
                    </div>
                </div>

                <!-- Right: Image -->
                <div class="hero-image-side">
                    <img src="images/agente-ia-iconovirtual.png" alt="Agente IA Icono Virtual" class="hero-image">
                </div>
            </div>
        </div>
    </section>

    <!-- Services Section - Full Screen Scroll Snap -->
    <div class="services-container">
        <!-- Hero section is technically outside, but we can make the container start after hero or include hero in it if we want full snap for everything. 
             For now, keeping the user's request: header buttons jump to sections. -->

        <?php foreach ($content['services'] as $index => $service): ?>
        <section class="service-slide <?php echo $service['css_class']; ?>-section"
            id="service-<?php echo $service['id']; ?>">
            <div class="container">
                <div class="service-content-wrapper <?php echo($index % 2 === 0) ? 'is-reversed' : ''; ?>">
                    <!-- Text Side -->
                    <div class="service-text" data-aos="fade-right">
                        <div class="agent-name">
                            <?php echo $service['agent_name']; ?>
                        </div>
                        <h2 class="service-heading">
                            <?php echo $service['title']; ?>
                        </h2>
                        <p class="service-desc-main">
                            <?php echo $service['description']; ?>
                        </p>
                        <ul class="service-details">
                            <?php foreach ($service['details'] as $detail): ?>
                            <li>
                                <?php echo preg_replace('/\*\*(.*?)\*\*/', '<strong>$1</strong>', $detail); ?>
                            </li>
                            <?php
    endforeach; ?>
                        </ul>
                        <a href="https://wa.me/<?php echo $content['whatsapp']['number']; ?>?text=<?php echo urlencode('Hola, me gustaría tener más información sobre ' . $service['title']); ?>"
                            target="_blank" class="btn-more-info">
                            <?php echo $service['cta']; ?>
                            <span class="btn-arrow">→</span>
                        </a>
                    </div>

                    <!-- Visual Side -->
                    <div class="service-visual" data-aos="fade-left">
                        <img src="images/<?php echo $service['image']; ?>" alt="<?php echo $service['agent_name']; ?>"
                            class="service-image" loading="lazy">
                    </div>
                </div>
            </div>
        </section>
        <?php
endforeach; ?>

        <!-- Footer Section (Inside Scroll Snap or separate?) User said "Footer button", implies it's a section. 
             Let's make it the last snap section. -->
        <!-- Footer Section (Minimalist Split) -->
        <section class="service-slide footer-section" id="footer"
            style="height: auto; min-height: 100vh; background: var(--color-bg-off-white);">
            <div class="container">
                <div class="footer-layout-split">
                    <!-- Left: Contact Info -->
                    <div class="footer-info-side" data-aos="fade-right">
                        <h2 class="contact-title">
                            <?php echo $content['form']['title']; ?>
                        </h2>
                        <div class="contact-item">
                            <div class="contact-icon"><i class="fas fa-phone"></i></div>
                            <div>
                                <strong>Llámanos</strong><br>
                                <?php echo $content['whatsapp']['number']; ?>
                            </div>
                        </div>
                        <div class="contact-item">
                            <div class="contact-icon"><i class="fas fa-envelope"></i></div>
                            <div>
                                <strong>Escríbenos</strong><br>
                                <?php echo $content['whatsapp']['email']; ?>
                            </div>
                        </div>

                        <div class="social-links" style="margin-top: 2rem;">
                            <a href="https://wa.me/<?php echo $content['whatsapp']['number']; ?>" class="social-link">
                                <i class="fab fa-whatsapp"></i> WhatsApp
                            </a>
                        </div>

                        <div class="footer-copyright"
                            style="text-align: left; border: none; padding-top: 2rem; margin-top: auto;">
                            <p>Copyright ©
                                <?php echo date('Y'); ?> • Icono Virtual S.A.S.
                            </p>
                        </div>
                    </div>

                    <!-- Right: Form -->
                    <div class="footer-form-side" data-aos="fade-left">
                        <h3 class="form-title">Envíanos un mensaje</h3>
                        <form id="contactForm" action="process.php" method="POST">
                            <div class="form-group">
                                <input type="text" name="name"
                                    placeholder="<?php echo $content['form']['fields']['name']; ?>" required>
                            </div>
                            <div class="form-group">
                                <input type="email" name="email"
                                    placeholder="<?php echo $content['form']['fields']['email']; ?>" required>
                            </div>
                            <div class="form-group">
                                <input type="tel" name="phone"
                                    placeholder="<?php echo $content['form']['fields']['phone']; ?>" required>
                            </div>
                            <div class="form-group">
                                <select name="service" required>
                                    <option value="">
                                        <?php echo $content['form']['fields']['service']; ?>
                                    </option>
                                    <?php foreach ($content['services'] as $service): ?>
                                    <option value="<?php echo $service['agent_name']; ?>">
                                        <?php echo $service['agent_name']; ?>
                                    </option>
                                    <?php
endforeach; ?>
                                </select>
                            </div>
                            <button type="submit" class="btn-primary" style="width: 100%;">
                                <?php echo $content['form']['button']; ?>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        </section>
    </div>

    <!-- Scroll Navigation -->
    <div class="scroll-nav">
        <!-- Hero Dot -->
        <div class="scroll-dot" onclick="document.getElementById('hero').scrollIntoView({behavior: 'smooth'})"
            data-label="Inicio"></div>

        <?php foreach ($content['services'] as $index => $service): ?>
        <div class="scroll-dot"
            onclick="document.getElementById('service-<?php echo $service['id']; ?>').scrollIntoView({behavior: 'smooth'})"
            data-label="<?php echo $service['agent_name']; ?>">
        </div>
        <?php
endforeach; ?>

        <!-- Footer Dot -->
        <div class="scroll-dot" onclick="document.getElementById('footer').scrollIntoView({behavior: 'smooth'})"
            data-label="Contacto"></div>
    </div>


    <!-- WhatsApp Floating Button -->
    <a href="https://wa.me/<?php echo $content['whatsapp']['number']; ?>?text=<?php echo urlencode($content['whatsapp']['message']); ?>"
        class="whatsapp-float" target="_blank" aria-label="Contactar por WhatsApp">
        <svg viewBox="0 0 24 24" fill="currentColor">
            <path
                d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.89-5.335 11.893-11.893a11.821 11.821 0 00-3.48-8.413Z" />
        </svg>
    </a>

    <!-- Mobile Bottom Navigation -->
    <nav class="mobile-bottom-nav">
        <?php
$icons = ['1' => 'fas fa-bullhorn', '2' => 'fas fa-calendar-alt', '3' => 'fas fa-comments'];
foreach ($content['services'] as $s):
?>
        <a href="#service-<?php echo $s['id']; ?>" class="mobile-nav-item">
            <i class="<?php echo $icons[$s['id']] ?? 'fas fa-robot'; ?>"></i>
            <span>
                <?php echo str_replace('Agente IA ', '', $s['agent_name']); ?>
            </span>
        </a>
        <?php
endforeach; ?>
        <a href="#footer" class="mobile-nav-item">
            <i class="fas fa-envelope"></i>
            <span>Contacto</span>
        </a>
    </nav>

    <script src="js/scroll-nav.js"></script>
    <script src="js/google-ads-tracking.js"></script>
    <script src="js/main.js"></script>
    <script src="js/parallax.js"></script>
</body>

</html>