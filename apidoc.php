<?php

/**
 * @api {get} /favorites/:token/:offset/:limit Mendapatkan Seluruh Surat Favorite
 * @apiVersion 0.1.0
 * @apiName GetSuratFavorite
 * @apiGroup Surat
 *
 * @apiParam {String} token Users unique token.
 * @apiParam {Integer} offset Offset data.
 * @apiParam {Integer} limit Limit data.
 * 
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {"count": 1, "isUnreads": 1, "isFavorites": 0, "isUnsigned": 30, 
 *      "result": [{
 *          "pengirim":"PUSTIKOM",
 *          "id":"6",
 *          "hal":"Akreditasi", 
 *          "subject":"Contoh submit surat",
 *          "role":"003000001",
 *          "notif_web":false,
 *          "notif_app":false,
 *          "isFavorite":false,
 *          "isUnread":true,
 *          "no_surat":"3\/UN39.18\/AK\/15",
 *          "lampiran":"0",
 *          "namaPenandatangan":"M. Ficky Duskarnaen",
 *          "jabatanPenandatangan":"Kepala UPT Pusat Komputer",
 *          "tanggal":"03 November 2015",
 *          "isi":"<p>Contoh response yang berhasil<\/p>",
 *          "tembusan":"Kepala UPT Pusat Komputer",
 *          "file_lampiran":[],
 *          "isUploaded":false,
 *          "uploadedFilePath":"assets\/uploaded\/283_META MODEL NLP revisi.pdf"}]
 *     }
 */

/**
 * @api {get} /suratsKeluar/:token/:offset/:limit Mendapatkan Seluruh Surat Keluar
 * @apiVersion 0.1.0
 * @apiName GetSuratKeluar
 * @apiGroup Surat
 *
 * @apiParam {String} token Users unique token.
 * @apiParam {Integer} offset Offset data.
 * @apiParam {Integer} limit Limit data.
 * 
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {"count": 1, "isUnreads": 1, "isFavorites": 0, "isUnsigned": 30, 
 *      "result": [{
 *          "pengirim":"PUSTIKOM",
 *          "id":"6",
 *          "hal":"Akreditasi", 
 *          "subject":"Contoh submit surat",
 *          "role":"003000001",
 *          "notif_web":false,
 *          "notif_app":false,
 *          "isFavorite":false,
 *          "isUnread":true,
 *          "no_surat":"3\/UN39.18\/AK\/15",
 *          "lampiran":"0",
 *          "namaPenandatangan":"M. Ficky Duskarnaen",
 *          "jabatanPenandatangan":"Kepala UPT Pusat Komputer",
 *          "tanggal":"03 November 2015",
 *          "isi":"<p>Contoh response yang berhasil<\/p>",
 *          "tembusan":"Kepala UPT Pusat Komputer",
 *          "file_lampiran":[],
 *          "isUploaded":false,
 *          "uploadedFilePath":"assets\/uploaded\/283_META MODEL NLP revisi.pdf"}]
 *     }
 */

/**
 * @api {get} /surats/:token/:offset/:limit Mendapatkan Seluruh Surat Masuk
 * @apiVersion 0.1.0
 * @apiName GetSurat
 * @apiGroup Surat
 *
 * @apiParam {String} token Users unique token.
 * @apiParam {Integer} offset Offset data.
 * @apiParam {Integer} limit Limit data.
 * 
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {"count": 1, "isUnreads": 1, "isFavorites": 0, "isUnsigned": 30, 
 *      "result": [{
 *          "pengirim":"PUSTIKOM",
 *          "id":"6",
 *          "hal":"Akreditasi", 
 *          "subject":"Contoh submit surat",
 *          "role":"003000001",
 *          "notif_web":false,
 *          "notif_app":false,
 *          "isFavorite":false,
 *          "isUnread":true,
 *          "no_surat":"3\/UN39.18\/AK\/15",
 *          "lampiran":"0",
 *          "namaPenandatangan":"M. Ficky Duskarnaen",
 *          "jabatanPenandatangan":"Kepala UPT Pusat Komputer",
 *          "tanggal":"03 November 2015",
 *          "isi":"<p>Contoh response yang berhasil<\/p>",
 *          "tembusan":"Kepala UPT Pusat Komputer",
 *          "file_lampiran":[],
 *          "isUploaded":false,
 *          "uploadedFilePath":"assets\/uploaded\/283_META MODEL NLP revisi.pdf"}]
 *     }
 */

/**
 * @api {post} /login User Login
 * @apiVersion 0.1.0
 * @apiName AuthLogin
 * @apiGroup Auth
 *
 * @apiParam {String} account User's Account.
 * @apiParam {String} password User's Password.
 * 
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {"status":true,
 *      "token":"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhY2NvdW50IjoiZmlja3lfZHVza2FybmFlbiIsIm5hbWEiOiJNLiBGaWNreSBEdXNrYXJuYWVuIiwiaWRfamFiYXRhbiI6IjAwMzAwMDAwMSIsImlkX2luc3RpdHVzaSI6IjAwMzAwMCIsIm5hbWFfaW5zdGl0dXNpIjoiUFVTVElLT00iLCJqZW5pc191c2VyIjoiMyIsIm5vaHAiOiIwODEyODk0MTc3OTkiLCJlbWFpbCI6ImZpcmRhdXNpYm51QGhvdG1haWwuY29tIiwidmFsaWQiOnRydWV9.LCgSGs3sDUrt7m3_FFnjiepF9Iw7nykxj-_mLD0tYNU",
 *      "userid":"2",
 *      "account":"ficky_duskarnaen",
 *      "nama":"M. Ficky Duskarnaen",
 *      "jabatan":"003000001",
 *      "institusi":"003000",
 *      "nama_institusi":"PUSTIKOM",
 *      "jenis_user":"3",
 *      "nohp":"081289417799",
 *      "email":"firdausibnu@hotmail.com",
 *      "isUnreads":1,
 *      "isFavorites":0,
 *      "isUnsigned":31,
 *      "isCorrected":2
 *      }
 */

/**
 * @api {post} /registerGCMUser Register User's GCM Reg ID to Database
 * @apiVersion 0.1.0
 * @apiName AuthRegID
 * @apiGroup Auth
 *
 * @apiParam {String} gcm_regid User's GCM Reg ID.
 * @apiParam {String} token User's Token.
 * 
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {"status":true,
 *      "event":"createGCMUser"
 *      }
 */

/**
 * @api {post} /unregisterGCMUser Unregister User's GCM Reg ID from Database
 * @apiVersion 0.1.0
 * @apiName AuthUnregID
 * @apiGroup Auth
 *
 * @apiParam {String} gcm_regid User's GCM Reg ID.
 * 
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {"status":true}
 */

/**
 * @api {put} /accSurat Accept Surat
 * @apiVersion 0.1.0
 * @apiName AccSurat
 * @apiGroup Surat
 *
 * @apiParam {String} token User's Token.
 * @apiParam {Integer} id_surat Surat's ID.
 * 
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {"countEmail": 1,
 *      "isUnreads": 5, 
 *      "isFavorites": 1, 
 *      "isUnsigned": 29, 
 *      "result": {"multicast_id":6412587390025910550,
 *                 "success":1,
 *                 "failure":0,
 *                 "canonical_ids":0,
 *                 "results":[{"message_id":"0:1448809187076391%a96836d8f9fd7ecd"}]
 *                 }
 *      }
 */

/**
 * @api {put} /koreksiSurat Koreksi Surat
 * @apiVersion 0.1.0
 * @apiName KoreksiSurat
 * @apiGroup Surat
 *
 * @apiParam {String} token User's Token.
 * @apiParam {String} pesan User's Pesan.
 * @apiParam {Integer} id_surat Surat's ID.
 * 
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {"result": "Success", 
 *      "account": "003000", 
 *      "isCorrected": 4
 *      }
 */

/**
 * @api {put} /setFavorite Set Favorite Surat
 * @apiVersion 0.1.0
 * @apiName SetFavoriteSurat
 * @apiGroup Surat
 *
 * @apiParam {String} token User's Token.
 * @apiParam {Boolean} status Surat's Favorite Status.
 * @apiParam {Integer} id_surat Surat's ID.
 * 
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {"isFavorites": 2, 
 *      "result": {"status":true}
 *      }
 */

/**
 * @api {put} /setRead Set Read Surat
 * @apiVersion 0.1.0
 * @apiName SetReadSurat
 * @apiGroup Surat
 *
 * @apiParam {String} token User's Token.
 * @apiParam {Integer} id_surat Surat's ID.
 * 
 * @apiSuccessExample {json} Success-Response:
 *     HTTP/1.1 200 OK
 *     {"isUnreads": 5, "result": {"status":true}}
 */