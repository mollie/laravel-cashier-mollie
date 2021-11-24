/**
 * Welcome to your Workbox-powered service worker!
 *
 * You'll need to register this file in your web app and you should
 * disable HTTP caching for this file too.
 * See https://goo.gl/nhQhGp
 *
 * The rest of the code is auto-generated. Please don't update this file
 * directly; instead, make changes to your Workbox build configuration
 * and re-run your build process.
 * See https://goo.gl/2aRDsh
 */

importScripts("https://storage.googleapis.com/workbox-cdn/releases/4.3.1/workbox-sw.js");

self.addEventListener('message', (event) => {
  if (event.data && event.data.type === 'SKIP_WAITING') {
    self.skipWaiting();
  }
});

/**
 * The workboxSW.precacheAndRoute() method efficiently caches and responds to
 * requests for URLs in the manifest.
 * See https://goo.gl/S9QRab
 */
self.__precacheManifest = [
  {
    "url": "01-installation.html",
    "revision": "3f77f4fa5bab9b3c0d289b0634a9fd34"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "a9dacd6cf362f7531b1600ffe1a37fa0"
  },
  {
    "url": "03-trials.html",
    "revision": "193e15ca77ca71324fea4dfd87e4d2a3"
  },
  {
    "url": "04-charges.html",
    "revision": "8bb9e4580b7e99b494cbeb5d1f37c1b6"
  },
  {
    "url": "05-metered.html",
    "revision": "c727ed9104f48abb49ae8621019d59b9"
  },
  {
    "url": "06-customer.html",
    "revision": "a5fa3318b8d7a942ecd94b4b4c541135"
  },
  {
    "url": "07-invoices.html",
    "revision": "db4e77c59d4c296ede9ee88ae7534845"
  },
  {
    "url": "08-refunds.html",
    "revision": "f29293e17fe1748304dd35df971eb810"
  },
  {
    "url": "09-events.html",
    "revision": "c3db95442c31ff34b8a00fb51ae59691"
  },
  {
    "url": "10-webhook.html",
    "revision": "19a2083ed803e8a448ff53700bbc3ee6"
  },
  {
    "url": "11-testing.html",
    "revision": "22ade98345b75a521dc94f010afbf595"
  },
  {
    "url": "12-faq.html",
    "revision": "b91154578529ff008424af973d48bb6d"
  },
  {
    "url": "13-upgrade.html",
    "revision": "af8fa4d49c652b1afa5c25c2f6ed286d"
  },
  {
    "url": "14-retry.html",
    "revision": "b0942c5902d20a18bdac80f6b42c3132"
  },
  {
    "url": "404.html",
    "revision": "de512f1e6554b8774157acb895d890ba"
  },
  {
    "url": "android-chrome-192x192.png",
    "revision": "7f1890f254594de8c4b514dee90ed629"
  },
  {
    "url": "android-chrome-384x384.png",
    "revision": "bc31b03048d4a3ba4fe82ce2389b0b38"
  },
  {
    "url": "apple-touch-icon.png",
    "revision": "19f3e3722c9d450ecfa73e8a92aaa47a"
  },
  {
    "url": "assets/css/0.styles.8d363c57.css",
    "revision": "86b0fd77cc538defc1d3f70fc50d22c8"
  },
  {
    "url": "assets/img/cashier-mollie.svg",
    "revision": "06f0ab467b31062098cffb5ff5d18bc6"
  },
  {
    "url": "assets/img/laravelcashiermollie.a7bde0e4.jpg",
    "revision": "a7bde0e4173f90acd2d72e0eb69d2764"
  },
  {
    "url": "assets/img/search.83621669.svg",
    "revision": "83621669651b9a3d4bf64d1a670ad856"
  },
  {
    "url": "assets/js/10.231f0486.js",
    "revision": "740eafa4f21d483b8b8622f44866c3b0"
  },
  {
    "url": "assets/js/11.6391d808.js",
    "revision": "f130758c1efd9fb7cea40e5f05119804"
  },
  {
    "url": "assets/js/12.431d6557.js",
    "revision": "6c0a5553774d385d3691b70c7e3a999d"
  },
  {
    "url": "assets/js/13.fa93f73e.js",
    "revision": "f71922bacbbe81a4b5dbb59b7ce02f12"
  },
  {
    "url": "assets/js/14.5bbefe7b.js",
    "revision": "7e3dbfb91bb140c8d580fae963d800e0"
  },
  {
    "url": "assets/js/15.45e1367d.js",
    "revision": "a99443c8f6006da673aa7c44548fa33c"
  },
  {
    "url": "assets/js/16.898fb784.js",
    "revision": "29af111f6c4bd16d6ae63550fbd51c6c"
  },
  {
    "url": "assets/js/17.11123fae.js",
    "revision": "9a76f0699a934cbb7ce2d38ec9e5bdf5"
  },
  {
    "url": "assets/js/18.d7895983.js",
    "revision": "265f7cb5920527ab76569bef90b7777e"
  },
  {
    "url": "assets/js/19.181ddefa.js",
    "revision": "6b3a395a3dacc343f5b8038cfeca88d3"
  },
  {
    "url": "assets/js/2.a409a020.js",
    "revision": "652e163d8c63cedffe86162734d234ec"
  },
  {
    "url": "assets/js/20.06b8ef97.js",
    "revision": "c8b4d3d80843013ea255c15bc602d828"
  },
  {
    "url": "assets/js/21.9b096231.js",
    "revision": "11f53d427785a77fcc14e68e0af3e7a0"
  },
  {
    "url": "assets/js/22.13fad203.js",
    "revision": "e3fe8cd8c5aa387b5f6fa2b5e70b47ed"
  },
  {
    "url": "assets/js/23.ef7d42a5.js",
    "revision": "d52ba350089bd6c80751311b5c975491"
  },
  {
    "url": "assets/js/3.fa274cbf.js",
    "revision": "daa4fa3dbcff43553926ec9e0117302e"
  },
  {
    "url": "assets/js/4.9631845c.js",
    "revision": "a873768cb6e945576742133544827dc5"
  },
  {
    "url": "assets/js/5.1681f43e.js",
    "revision": "7921397f289b9662c76a5ab21fa299f3"
  },
  {
    "url": "assets/js/6.3795202e.js",
    "revision": "e74829f64c1be1aa20ff383591a43027"
  },
  {
    "url": "assets/js/7.65ee5971.js",
    "revision": "1633e22cb2ca8869fd825ca752f49e67"
  },
  {
    "url": "assets/js/8.60b99c60.js",
    "revision": "9b3bb5635a52c2b1af2edd9335a51a6f"
  },
  {
    "url": "assets/js/9.2682c50d.js",
    "revision": "b5ae2fefe8bed690061cceca608c4c34"
  },
  {
    "url": "assets/js/app.5d45d7cc.js",
    "revision": "b0f84b1023b15488211f05928a938874"
  },
  {
    "url": "assets/pages/laravelcashiermollie.jpg",
    "revision": "a7bde0e4173f90acd2d72e0eb69d2764"
  },
  {
    "url": "favicon-16x16.png",
    "revision": "e8cead60a31ba0059df44368227bba35"
  },
  {
    "url": "favicon-32x32.png",
    "revision": "2f21759d559a5e952851228adbb628ec"
  },
  {
    "url": "index.html",
    "revision": "721c204952e2988116cda97df0ae4d0f"
  }
].concat(self.__precacheManifest || []);
workbox.precaching.precacheAndRoute(self.__precacheManifest, {});
addEventListener('message', event => {
  const replyPort = event.ports[0]
  const message = event.data
  if (replyPort && message && message.type === 'skip-waiting') {
    event.waitUntil(
      self.skipWaiting().then(
        () => replyPort.postMessage({ error: null }),
        error => replyPort.postMessage({ error })
      )
    )
  }
})
