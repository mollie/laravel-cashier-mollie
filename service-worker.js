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
    "revision": "f4aebe71922cf6cbb1b674c7533f9900"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "ad91755c0c0b493646395308cd7b257a"
  },
  {
    "url": "03-trials.html",
    "revision": "53265a02097fe909363e8dde649acda9"
  },
  {
    "url": "04-charges.html",
    "revision": "a3d3c062cc0067ac420b928c37f53cb0"
  },
  {
    "url": "05-metered.html",
    "revision": "fd82ec0521f2350fcc29b1723227ad00"
  },
  {
    "url": "06-customer.html",
    "revision": "dc9b3b9c7bf2071bad62e336d1a265c5"
  },
  {
    "url": "07-invoices.html",
    "revision": "fdd9c765a9ccaf3b04d339a07da705cc"
  },
  {
    "url": "08-refunds.html",
    "revision": "b5e7a568dee47daeb58869ce3f6ffc9c"
  },
  {
    "url": "09-events.html",
    "revision": "25453ee7125a82a744b76ea918037ffc"
  },
  {
    "url": "10-webhook.html",
    "revision": "6f18d86bf77b9d4a2b6675ed518b08bf"
  },
  {
    "url": "11-testing.html",
    "revision": "77b1b204041d5d6cf0f63695cfde2227"
  },
  {
    "url": "12-faq.html",
    "revision": "c10acfcdf88432a8d54dda84d6c81520"
  },
  {
    "url": "13-upgrade.html",
    "revision": "5228275473ef61300519edcb979f8c94"
  },
  {
    "url": "14-retry.html",
    "revision": "1853ca29fc207e06482ed1729bc9c521"
  },
  {
    "url": "15-localization.html",
    "revision": "cf35c602fa897747fa8eaab1d15c73cb"
  },
  {
    "url": "16-configuration.html",
    "revision": "11576e288484d6381aedf5c287e36d0d"
  },
  {
    "url": "404.html",
    "revision": "51ebff142c3e198f122c3f77e1b49323"
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
    "url": "assets/js/app.a3d374a0.js",
    "revision": "fbfe1910d4a38729164255cefe2a3ac3"
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
    "revision": "7c057f59d70ead5a0705450eeccaeda4"
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
