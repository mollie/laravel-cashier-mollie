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
    "revision": "9963beff8c63694c51442301b6171aad"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "1eb3192edab539dc24e1c04cad189efb"
  },
  {
    "url": "03-trials.html",
    "revision": "c01ab29ff38e1424cb9ed62185bb8a08"
  },
  {
    "url": "04-charges.html",
    "revision": "35d27d868b0e191a93ee2e39d3ca5507"
  },
  {
    "url": "05-metered.html",
    "revision": "8c5f93618a930757f7aa32a7bd865223"
  },
  {
    "url": "06-customer.html",
    "revision": "5a149ab293e6e01ea9fb93dacf4d759d"
  },
  {
    "url": "07-invoices.html",
    "revision": "f22333b2700f2c87f336cc60595a6355"
  },
  {
    "url": "08-refunds.html",
    "revision": "df4d118ee3d20fc5d6bb728b8b78bb36"
  },
  {
    "url": "09-events.html",
    "revision": "46e2fd3f9cef38b174a7d0e74efa8116"
  },
  {
    "url": "10-webhook.html",
    "revision": "3bf46177a56ec6952081518a95bc5361"
  },
  {
    "url": "11-testing.html",
    "revision": "7ca7ed6fcd7e75b5b5b05ddd637b6f98"
  },
  {
    "url": "12-faq.html",
    "revision": "f280a13202f12df74f186e09347733f9"
  },
  {
    "url": "13-upgrade.html",
    "revision": "882f538816c01a30bef10ed5f61253c8"
  },
  {
    "url": "14-retry.html",
    "revision": "a05c1469fedb3342f11c8ee23c543497"
  },
  {
    "url": "15-localization.html",
    "revision": "6c90231cd8266cbd3a57e9e25ebac27b"
  },
  {
    "url": "16-configuration.html",
    "revision": "4e49c6f299ac07c36e5ff26807e305de"
  },
  {
    "url": "404.html",
    "revision": "811598e7110c9f50e81af7c58cda9d16"
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
    "url": "assets/js/10.7342a03d.js",
    "revision": "425e8a1e5d106efee090b9a23f3bfe3a"
  },
  {
    "url": "assets/js/11.9b6e11a4.js",
    "revision": "7ba372f95f30ede3db2741a1c5e78f8e"
  },
  {
    "url": "assets/js/12.66f6e6d0.js",
    "revision": "0c345f4caf618cd615daab404dad994c"
  },
  {
    "url": "assets/js/13.daee5b68.js",
    "revision": "1e2c275b99ff05e6dd2f71cdf5c8a52a"
  },
  {
    "url": "assets/js/14.d2101241.js",
    "revision": "d1af448993e584405564cc208851b4d0"
  },
  {
    "url": "assets/js/15.30e575f0.js",
    "revision": "1b55f8664a73a2e1adb96e693d11e278"
  },
  {
    "url": "assets/js/16.c70b6df7.js",
    "revision": "8af11119d400a1fcb236be7441a033d1"
  },
  {
    "url": "assets/js/17.4adb4204.js",
    "revision": "e1bc0728ef8e88a42e838f2eda6aad71"
  },
  {
    "url": "assets/js/18.7257c54b.js",
    "revision": "a8a2ca01338a103e9ee6e8d077eebe3f"
  },
  {
    "url": "assets/js/19.e02cf0b7.js",
    "revision": "50026b36cd40fa44f0304ccd8aa1276d"
  },
  {
    "url": "assets/js/2.635f01f1.js",
    "revision": "4fb662d2c0f978864200a431ef71ea09"
  },
  {
    "url": "assets/js/20.9dfe29df.js",
    "revision": "2d0865091b5c0cb8b94a416e4607bf28"
  },
  {
    "url": "assets/js/21.d0a76f03.js",
    "revision": "dfb0ed3d19f88d1a35e6313d746f5036"
  },
  {
    "url": "assets/js/22.9ec441ad.js",
    "revision": "38886883ef31671f71240e69802b8a72"
  },
  {
    "url": "assets/js/23.f89527ad.js",
    "revision": "11e62e502312880e1675b9c091d4c2ba"
  },
  {
    "url": "assets/js/24.551447f0.js",
    "revision": "9827681db9575c2705a8a616bdf41e2b"
  },
  {
    "url": "assets/js/25.7f7bb020.js",
    "revision": "9173310ebb42bf6879644486f3cebe1f"
  },
  {
    "url": "assets/js/3.c82dd2ac.js",
    "revision": "b4d2e5c4462273381448d9f8cd5bbded"
  },
  {
    "url": "assets/js/4.3365c8f5.js",
    "revision": "9f078db9908a36b75d91b4e6a1cf5ee1"
  },
  {
    "url": "assets/js/5.3ba091ff.js",
    "revision": "ff4be256e6492ce9c5a2ca794ab49518"
  },
  {
    "url": "assets/js/6.c42150ef.js",
    "revision": "e064db4de14b6e46fb1efcd223ec0cf2"
  },
  {
    "url": "assets/js/7.d7565801.js",
    "revision": "2f5810139a93f63c52f16091917e5fe1"
  },
  {
    "url": "assets/js/8.50cab0eb.js",
    "revision": "d38e9a3e0c223d5faaa4dffd73ed162b"
  },
  {
    "url": "assets/js/9.8fb5284c.js",
    "revision": "47375a47be95567050542d19f821705f"
  },
  {
    "url": "assets/js/app.eb41919e.js",
    "revision": "76b694fca828d581dfa83d17835dbe98"
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
    "revision": "a6a10c8da6c791b0f822018cf747b39b"
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
