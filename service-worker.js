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
    "revision": "66745f085e77d766fff32cfc975410d2"
  },
  {
    "url": "02-subscriptions.html",
    "revision": "d73b0a0b9736c95e1aa8ba6da997043f"
  },
  {
    "url": "03-trials.html",
    "revision": "5e4e89e5ea9b327d3eeb23eca5fdceb7"
  },
  {
    "url": "04-charges.html",
    "revision": "b78ab566c3b6886eae77575634140b16"
  },
  {
    "url": "05-metered.html",
    "revision": "13b51c436b713ae0173233244548ebf7"
  },
  {
    "url": "06-customer.html",
    "revision": "7833dbc4033e37fc3b47fbbb07472647"
  },
  {
    "url": "07-invoices.html",
    "revision": "c7e0e0702bca8466cc2dfef149c15efb"
  },
  {
    "url": "08-refunds.html",
    "revision": "1f367e558a7309c28eadfd8a2d49781a"
  },
  {
    "url": "09-events.html",
    "revision": "c599ae6bd32314b1af49418d03424847"
  },
  {
    "url": "10-webhook.html",
    "revision": "8e24c9b41029ce6e8085b61c65cf9531"
  },
  {
    "url": "11-testing.html",
    "revision": "221942ff854c9b00bcc0722c0fde2ab6"
  },
  {
    "url": "12-faq.html",
    "revision": "503efc4dd9ee960e1d5143c00aebf501"
  },
  {
    "url": "13-upgrade.html",
    "revision": "e2c494106e216cd17d4f40916955c3a2"
  },
  {
    "url": "14-retry.html",
    "revision": "a90a55c609764e01745ee6ad09b2c164"
  },
  {
    "url": "15-localization.html",
    "revision": "cd6b33331f9a2fa9597babd0815cd507"
  },
  {
    "url": "16-configuration.html",
    "revision": "39bdc7013b1494b58dbd63254cb2e5c9"
  },
  {
    "url": "404.html",
    "revision": "a7e56db964a443ece424aec1697503d9"
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
    "url": "assets/css/0.styles.55b3e847.css",
    "revision": "0dc105425b8aff6d848214e9acbd76cb"
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
    "url": "assets/js/10.698d6252.js",
    "revision": "e39415ce413e366d6132ab74d7220b29"
  },
  {
    "url": "assets/js/11.a769ab4b.js",
    "revision": "863abe8885d09a67cb6eaf172e09ebf7"
  },
  {
    "url": "assets/js/12.120fa77a.js",
    "revision": "7c84070f955fc72cdcf65dc4af5411ca"
  },
  {
    "url": "assets/js/13.849c97e1.js",
    "revision": "9bb95e137bbfc61c92c8aa88fe652aed"
  },
  {
    "url": "assets/js/14.df13e39f.js",
    "revision": "d4c909f51c08453360a5ea93ab112fa1"
  },
  {
    "url": "assets/js/15.239bcd69.js",
    "revision": "0fc522221ada3ea9ce254ec2f6b4f5f3"
  },
  {
    "url": "assets/js/16.48b83b5f.js",
    "revision": "05def8573305ab89db005bf6c7d71402"
  },
  {
    "url": "assets/js/17.8220f295.js",
    "revision": "c5c65c2280f666c2a7e9e4755a9cb35d"
  },
  {
    "url": "assets/js/18.190988ec.js",
    "revision": "269c355912039d92f4e595b605cd3925"
  },
  {
    "url": "assets/js/19.2c5fd2f1.js",
    "revision": "e1eb5f071bae77e20428b28a3958dabe"
  },
  {
    "url": "assets/js/2.676c7395.js",
    "revision": "61ac83bed59d894594fd4ae767a74b9b"
  },
  {
    "url": "assets/js/20.3b129a9b.js",
    "revision": "11ee77ce1e192afc3f4bbdf49a39aea3"
  },
  {
    "url": "assets/js/21.c03b49ba.js",
    "revision": "5ab7a9f334238641c1c7f5dc28c1e18b"
  },
  {
    "url": "assets/js/22.37b4ba2c.js",
    "revision": "aa96618a39b50772aa1a6082952748df"
  },
  {
    "url": "assets/js/23.bf7129d1.js",
    "revision": "d1d15f98782770ac0cc5fd746430e741"
  },
  {
    "url": "assets/js/24.8094284f.js",
    "revision": "4eca465924c9800559dea849a65dc7d5"
  },
  {
    "url": "assets/js/25.d4ae5cb2.js",
    "revision": "e88b913d2a168ef7caf67dace1008edf"
  },
  {
    "url": "assets/js/3.341cd230.js",
    "revision": "3708a8c71b3055459fb97cdd8772788a"
  },
  {
    "url": "assets/js/4.962f095d.js",
    "revision": "77e3c8b1e1961e7c106f1a46a469cbb6"
  },
  {
    "url": "assets/js/5.37372cb8.js",
    "revision": "4f792f9dbbb25bddc2dfcf2e1511696f"
  },
  {
    "url": "assets/js/6.6786ae0f.js",
    "revision": "d90384fd1fe9bfd3fa101caaa3c2809f"
  },
  {
    "url": "assets/js/7.2c5c606b.js",
    "revision": "3ad87aac4f227933ae3af3e9c34f555d"
  },
  {
    "url": "assets/js/8.4d9a6b3b.js",
    "revision": "a04db9c69831e368f57ecec4a72d92d6"
  },
  {
    "url": "assets/js/9.6779aa27.js",
    "revision": "a1248f4b3669f58282afe3630b4480a0"
  },
  {
    "url": "assets/js/app.f4caeb1d.js",
    "revision": "42fc8d320c50281c047a092cd76fbfae"
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
    "revision": "0b9c1e01248c9ed77bffb400395529c3"
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
