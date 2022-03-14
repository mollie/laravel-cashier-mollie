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
    "revision": "c55a03f9bf7cac696ec7ad9d6508e119"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "c08411310073e0d85e45f3c97fe8e835"
  },
  {
    "url": "03-trials.html",
    "revision": "703a8a2e8b1569467b7b4c5f97162cca"
  },
  {
    "url": "04-charges.html",
    "revision": "909c6ec7b5db9b230f969086cf7105bf"
  },
  {
    "url": "05-metered.html",
    "revision": "dd1ac01b49ec4d90c6c453e9bd478ea2"
  },
  {
    "url": "06-customer.html",
    "revision": "56438ad465a3c291113d3d7e688d77a3"
  },
  {
    "url": "07-invoices.html",
    "revision": "09824eb524d7627400b030e3fbeb3883"
  },
  {
    "url": "08-refunds.html",
    "revision": "bac24811d31937396d38af0c7107932e"
  },
  {
    "url": "09-events.html",
    "revision": "22cc5bba15348d1e56737d49324fc42f"
  },
  {
    "url": "10-webhook.html",
    "revision": "6d91af79a291661a210315095b7753bf"
  },
  {
    "url": "11-testing.html",
    "revision": "8c193e7137ccfecea952502d3e507945"
  },
  {
    "url": "12-faq.html",
    "revision": "c0d0d2c909c2058f289db6453f177954"
  },
  {
    "url": "13-upgrade.html",
    "revision": "483f62e0da626c37023c139983a00260"
  },
  {
    "url": "14-retry.html",
    "revision": "994926cb67b778de68ccfcd304964361"
  },
  {
    "url": "15-localization.html",
    "revision": "dc2754e92109a82bc7d38acfc2b8552b"
  },
  {
    "url": "16-configuration.html",
    "revision": "4b2ef9ce3bf87d3a2b588720959dd6ca"
  },
  {
    "url": "404.html",
    "revision": "ca53295fce3cfdaac0024c8e483d24ec"
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
    "url": "assets/js/17.42b5c36e.js",
    "revision": "34140d1f133592453799e84321ca0dd0"
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
    "url": "assets/js/24.c50fc1a5.js",
    "revision": "cccd069f1b3b1840cf5ead79e83f862a"
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
    "url": "assets/js/app.71632d0b.js",
    "revision": "c857499493300b50adc0b5dc2f141390"
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
    "revision": "7e0b27a20707cabbdb371098130873a8"
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
