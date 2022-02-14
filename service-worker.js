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
    "revision": "bf79e832c7863c843c225a83239c359a"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "0375b1762130e2868e7bc4f80d3714da"
  },
  {
    "url": "03-trials.html",
    "revision": "927dbec8d84aa7360311f950db7f5134"
  },
  {
    "url": "04-charges.html",
    "revision": "abe6c344eb840d86516a58e85cd4dd2f"
  },
  {
    "url": "05-metered.html",
    "revision": "90aa2e844ae6ce1d48a549085acc81a1"
  },
  {
    "url": "06-customer.html",
    "revision": "37774b57ce3d1cbb3525d4f3576269f6"
  },
  {
    "url": "07-invoices.html",
    "revision": "ce6b4e7d0871bbbfed34175430361c40"
  },
  {
    "url": "08-refunds.html",
    "revision": "9e5da56fe3e741e9d9f4d854c4bb130e"
  },
  {
    "url": "09-events.html",
    "revision": "c3642490a220b0b098fe0fd3de14e26b"
  },
  {
    "url": "10-webhook.html",
    "revision": "d7a851a07729cc94fe4bb45c9b6ee360"
  },
  {
    "url": "11-testing.html",
    "revision": "f7cb8c760e8c95d36d941bcda9f1eece"
  },
  {
    "url": "12-faq.html",
    "revision": "0fcf6f6c879c46c37569931291689eee"
  },
  {
    "url": "13-upgrade.html",
    "revision": "d81afa559fdccc93fdcd404fc02fe9a7"
  },
  {
    "url": "14-retry.html",
    "revision": "5349cf6ea7cd20d9c4dfa3a12f302499"
  },
  {
    "url": "15-localization.html",
    "revision": "c3145315fe0eb5d492c3c834ad9bb8ef"
  },
  {
    "url": "16-configuration.html",
    "revision": "23c7b314c71743de96edbb4d0417dcf9"
  },
  {
    "url": "404.html",
    "revision": "7768e7bbf1830572d80f5771bab9729e"
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
    "url": "assets/js/11.016723b2.js",
    "revision": "ed72084a8d8fb420abcff25f37d08c42"
  },
  {
    "url": "assets/js/12.edf94aa9.js",
    "revision": "0e479560a1803838cf2335981c31c647"
  },
  {
    "url": "assets/js/13.094ae7cb.js",
    "revision": "18c23475513460069ffd9200ee37a583"
  },
  {
    "url": "assets/js/14.d2101241.js",
    "revision": "d1af448993e584405564cc208851b4d0"
  },
  {
    "url": "assets/js/15.0551e6be.js",
    "revision": "687b5b451427636c73eb02a59300edb2"
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
    "url": "assets/js/24.acd18fb5.js",
    "revision": "4c0bb3a7baa1cb7e728aae6f738f30b9"
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
    "url": "assets/js/7.b67f820b.js",
    "revision": "e9af797330160ed0989bd7c4f544d19f"
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
    "url": "assets/js/app.45e90aec.js",
    "revision": "f0df2433082b9fe0dd3a431286e3e009"
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
    "revision": "77935e406d222d287440a631309f52fa"
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
