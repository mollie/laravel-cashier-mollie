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
    "revision": "b1f0b902aec30a1de894eceb966e83b9"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "104ccb570df55230b5f143ea1f58067a"
  },
  {
    "url": "03-trials.html",
    "revision": "bab3cbda1b6d84677fd4a6b10312337e"
  },
  {
    "url": "04-charges.html",
    "revision": "bb77f1fedef5964865d2bd03444927b3"
  },
  {
    "url": "05-metered.html",
    "revision": "ea24c6f9a88a80fe620ecdcede4950ff"
  },
  {
    "url": "06-customer.html",
    "revision": "25e210523e344cfb2bde110ba6ba2169"
  },
  {
    "url": "07-invoices.html",
    "revision": "27603fa26d8666a129e3b2287e674733"
  },
  {
    "url": "08-refunds.html",
    "revision": "e9893a74a16d61cac902dc7cdb4b3adf"
  },
  {
    "url": "09-events.html",
    "revision": "bdc9d1a2b6ac0a0aa9f77dbd329200cb"
  },
  {
    "url": "10-webhook.html",
    "revision": "047d2060d38e897a779ea04fa6ea065a"
  },
  {
    "url": "11-testing.html",
    "revision": "cfb157f4d93c41bd476372cce4e9836f"
  },
  {
    "url": "12-faq.html",
    "revision": "d8aa1ac983212fe1ff2e5829e57937b8"
  },
  {
    "url": "13-upgrade.html",
    "revision": "2a6804bd3fc97f45560e7e031568a330"
  },
  {
    "url": "14-retry.html",
    "revision": "2f8fe3f0be323c67df6bb5567df9ae18"
  },
  {
    "url": "15-localization.html",
    "revision": "7896ae3b4032f144e8a7f8d456182ac8"
  },
  {
    "url": "404.html",
    "revision": "150bde040896965d3b041e38972f6235"
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
    "url": "assets/js/10.4bb8aa94.js",
    "revision": "7aee77342200ff59121b1d7f026b2979"
  },
  {
    "url": "assets/js/11.69edff1d.js",
    "revision": "8cb5dcd10a56d59dd118dd53dd0f4dd5"
  },
  {
    "url": "assets/js/12.aa8b36ea.js",
    "revision": "c42dcefafdc5052e7bdc53d424d27d4d"
  },
  {
    "url": "assets/js/13.2ebac815.js",
    "revision": "84441884586b6638d6acb9746e8ac297"
  },
  {
    "url": "assets/js/14.8ab8bad3.js",
    "revision": "d68a5546cd8e456dde07dfc9725302b7"
  },
  {
    "url": "assets/js/15.634217ba.js",
    "revision": "6cd8b5cc43658c47863275ed66faac03"
  },
  {
    "url": "assets/js/16.4887b3f2.js",
    "revision": "c38a0e602d17a3fc5ddd5ecdc529eb8b"
  },
  {
    "url": "assets/js/17.601908f7.js",
    "revision": "6e697fed36a53aa5a6ef9b8ba07f56d7"
  },
  {
    "url": "assets/js/18.fafa4fc6.js",
    "revision": "f750a4eb0b58728df2833d6777456fdf"
  },
  {
    "url": "assets/js/19.3dcbe86f.js",
    "revision": "0fa08ad3e7895e4eccf152952c784dfc"
  },
  {
    "url": "assets/js/2.732317f1.js",
    "revision": "49bd10962281b064e8e47803ca8d6770"
  },
  {
    "url": "assets/js/20.c26766c0.js",
    "revision": "ea015188582398ebf4a4752fd5c09cc0"
  },
  {
    "url": "assets/js/21.30502fce.js",
    "revision": "e5525bcfd48ac22ef0628ff682b5150a"
  },
  {
    "url": "assets/js/22.2410b9dc.js",
    "revision": "3985eebe72ac39f12f92bf6337b5095f"
  },
  {
    "url": "assets/js/23.d16235d8.js",
    "revision": "e797939ac684b257701a6a12d249bdbc"
  },
  {
    "url": "assets/js/24.2e1c18ef.js",
    "revision": "a746c8cc185696d2fee4d688b5be69d3"
  },
  {
    "url": "assets/js/3.42d67b2b.js",
    "revision": "ee848c1c08d0ee979cd9ca1e9da5ea80"
  },
  {
    "url": "assets/js/4.b1b7a693.js",
    "revision": "4576f597d4af7669c1d32618bceda83a"
  },
  {
    "url": "assets/js/5.75ae48a5.js",
    "revision": "edc08bef04c3b9323c9fd1414c44dcef"
  },
  {
    "url": "assets/js/6.bb2a77e9.js",
    "revision": "c3edc8f716dc0fd8c47b880358ac7768"
  },
  {
    "url": "assets/js/7.dc5e05ae.js",
    "revision": "ccd78aa775877ba810b2994e52b5f68c"
  },
  {
    "url": "assets/js/8.bc1d50a5.js",
    "revision": "b8e80e786123a4d1fca2c2a01b586c38"
  },
  {
    "url": "assets/js/9.ca3df7ed.js",
    "revision": "927eb17246972786eb596da4141495ca"
  },
  {
    "url": "assets/js/app.f9548df0.js",
    "revision": "d21ae92296f057438810bd72166692e9"
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
    "revision": "b4d2d9f68db49350246e6370fc23db6a"
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
