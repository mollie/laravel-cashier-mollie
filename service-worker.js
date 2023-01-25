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
    "revision": "44ec4116b9eb44c4433546eb9c021b72"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "97036c981f778f15f9422e42bad3e7fe"
  },
  {
    "url": "03-trials.html",
    "revision": "42fa8ea719bc8f1bc8efc9fb9ad4d9c1"
  },
  {
    "url": "04-charges.html",
    "revision": "231d27ec8696fd6ad8613f72e39343af"
  },
  {
    "url": "05-metered.html",
    "revision": "5aa7b7d417e585975effc08dc57c95c2"
  },
  {
    "url": "06-customer.html",
    "revision": "03d10fb1ad6c8fa393595bd3729305bb"
  },
  {
    "url": "07-invoices.html",
    "revision": "aca6524cb809d9fa7db2b5f8877b1a31"
  },
  {
    "url": "08-refunds.html",
    "revision": "48638b56d79a50cc5ed0b5e698001857"
  },
  {
    "url": "09-events.html",
    "revision": "3c53fb80a9a266c58ded03b45fe8ae87"
  },
  {
    "url": "10-webhook.html",
    "revision": "687dfd27a13296dfdf39a50e3c2100ad"
  },
  {
    "url": "11-testing.html",
    "revision": "38dc6fa1693373631369ea26fb80a8b5"
  },
  {
    "url": "12-faq.html",
    "revision": "b07a60d771790bbc8110e64a74e1df2c"
  },
  {
    "url": "13-upgrade.html",
    "revision": "8eb9d3835fb09d9386714dab6b4f5699"
  },
  {
    "url": "14-retry.html",
    "revision": "b19974d45e5732586f3a830157eb4285"
  },
  {
    "url": "15-localization.html",
    "revision": "84dfe40ced504a9733538b195ca6ddca"
  },
  {
    "url": "16-configuration.html",
    "revision": "2160a0f6d275e4700502b717054977d9"
  },
  {
    "url": "404.html",
    "revision": "d09cb92f4c2b492b7537a5e9171e7aa4"
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
    "url": "assets/css/0.styles.ce263dea.css",
    "revision": "94003a900c58188582215d92ada1316f"
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
    "url": "assets/js/10.96aa06f0.js",
    "revision": "ac1dfed68ea2a63cc7d50936a8991c2b"
  },
  {
    "url": "assets/js/11.c057ca47.js",
    "revision": "4ff72fc1ff0205bed8b54ea56f7432a2"
  },
  {
    "url": "assets/js/12.34b4ccf6.js",
    "revision": "c55cca7738262faf9c25c83296a818f0"
  },
  {
    "url": "assets/js/13.1d4b5e77.js",
    "revision": "a00034179a3578e7480f70be1112e99c"
  },
  {
    "url": "assets/js/14.eb181e6d.js",
    "revision": "16ae6591abf17b926b198ea01e1a832f"
  },
  {
    "url": "assets/js/15.a13666b5.js",
    "revision": "99626727ebf49058f9f55e496023ee6c"
  },
  {
    "url": "assets/js/16.00e94dc2.js",
    "revision": "50794c9b38d443548238bb8e2a8b0e23"
  },
  {
    "url": "assets/js/17.59bf1e51.js",
    "revision": "7dec2d23cae7a7a641bf419d0e494c51"
  },
  {
    "url": "assets/js/18.bd132323.js",
    "revision": "f4890a9c5a06d18c05fb042fb55a226d"
  },
  {
    "url": "assets/js/19.fa1b2029.js",
    "revision": "c596c8beca50c012d3b7cde7c781db47"
  },
  {
    "url": "assets/js/2.89110c92.js",
    "revision": "f89c874287b6a4926253a12bd0f9e42e"
  },
  {
    "url": "assets/js/20.a1f72387.js",
    "revision": "6b67d5e12ca113a739c2a9a6af3c7637"
  },
  {
    "url": "assets/js/21.e98a18b1.js",
    "revision": "e61c55990d632711789282a8e7833452"
  },
  {
    "url": "assets/js/22.7c0387e3.js",
    "revision": "0aca239d4fc98fd8053b77a925d0bce4"
  },
  {
    "url": "assets/js/23.eb503266.js",
    "revision": "41d22ea0f6770d5e053f912565d84acd"
  },
  {
    "url": "assets/js/24.76a0f743.js",
    "revision": "8035ae9ebef65a86ed636578cf3d52a1"
  },
  {
    "url": "assets/js/25.e9d4803b.js",
    "revision": "64efe396c29a1006fd21955bb473c02e"
  },
  {
    "url": "assets/js/3.52d247a0.js",
    "revision": "333abd8cb378d979755796fa9b34beae"
  },
  {
    "url": "assets/js/4.9bf4f97f.js",
    "revision": "e338088bee2d7c1f3a76fd8727b79f2e"
  },
  {
    "url": "assets/js/5.96d8d498.js",
    "revision": "a0d09cd8ce48c03b8081676b8cfc854a"
  },
  {
    "url": "assets/js/6.61f0909f.js",
    "revision": "f87c98839cad77561ea83f8745d18587"
  },
  {
    "url": "assets/js/7.5fbca5f1.js",
    "revision": "ce2fc06256991f6b8fd56cc89bc7daa7"
  },
  {
    "url": "assets/js/8.9c401d55.js",
    "revision": "ac456e070b81316510e7331e802be828"
  },
  {
    "url": "assets/js/9.0bfa9832.js",
    "revision": "59e0360e84176a4130d028485bb1be1b"
  },
  {
    "url": "assets/js/app.7374a94e.js",
    "revision": "090b24272146c194e691a362591dfa13"
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
    "revision": "acf1b660d6602f0715970f9dfd6f5c34"
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
