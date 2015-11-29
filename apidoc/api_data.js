define({ "api": [
  {
    "type": "post",
    "url": "/login",
    "title": "User Login",
    "version": "0.1.0",
    "name": "AuthLogin",
    "group": "Auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "account",
            "description": "<p>User's Account.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "password",
            "description": "<p>User's Password.</p> "
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\"status\":true,\n \"token\":\"eyJ0eXAiOiJKV1QiLCJhbGciOiJIUzI1NiJ9.eyJhY2NvdW50IjoiZmlja3lfZHVza2FybmFlbiIsIm5hbWEiOiJNLiBGaWNreSBEdXNrYXJuYWVuIiwiaWRfamFiYXRhbiI6IjAwMzAwMDAwMSIsImlkX2luc3RpdHVzaSI6IjAwMzAwMCIsIm5hbWFfaW5zdGl0dXNpIjoiUFVTVElLT00iLCJqZW5pc191c2VyIjoiMyIsIm5vaHAiOiIwODEyODk0MTc3OTkiLCJlbWFpbCI6ImZpcmRhdXNpYm51QGhvdG1haWwuY29tIiwidmFsaWQiOnRydWV9.LCgSGs3sDUrt7m3_FFnjiepF9Iw7nykxj-_mLD0tYNU\",\n \"userid\":\"2\",\n \"account\":\"ficky_duskarnaen\",\n \"nama\":\"M. Ficky Duskarnaen\",\n \"jabatan\":\"003000001\",\n \"institusi\":\"003000\",\n \"nama_institusi\":\"PUSTIKOM\",\n \"jenis_user\":\"3\",\n \"nohp\":\"081289417799\",\n \"email\":\"firdausibnu@hotmail.com\",\n \"isUnreads\":1,\n \"isFavorites\":0,\n \"isUnsigned\":31,\n \"isCorrected\":2\n }",
          "type": "json"
        }
      ]
    },
    "filename": "./apidoc.php",
    "groupTitle": "Auth"
  },
  {
    "type": "post",
    "url": "/registerGCMUser",
    "title": "Register User's GCM Reg ID to Database",
    "version": "0.1.0",
    "name": "AuthRegID",
    "group": "Auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "gcm_regid",
            "description": "<p>User's GCM Reg ID.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "token",
            "description": "<p>User's Token.</p> "
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\"status\":true,\n \"event\":\"createGCMUser\"\n }",
          "type": "json"
        }
      ]
    },
    "filename": "./apidoc.php",
    "groupTitle": "Auth"
  },
  {
    "type": "post",
    "url": "/unregisterGCMUser",
    "title": "Unregister User's GCM Reg ID from Database",
    "version": "0.1.0",
    "name": "AuthUnregID",
    "group": "Auth",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "gcm_regid",
            "description": "<p>User's GCM Reg ID.</p> "
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\"status\":true}",
          "type": "json"
        }
      ]
    },
    "filename": "./apidoc.php",
    "groupTitle": "Auth"
  },
  {
    "type": "put",
    "url": "/accSurat",
    "title": "Accept Surat",
    "version": "0.1.0",
    "name": "AccSurat",
    "group": "Surat",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "token",
            "description": "<p>User's Token.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>Integer</p> ",
            "optional": false,
            "field": "id_surat",
            "description": "<p>Surat's ID.</p> "
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\"countEmail\": 1,\n \"isUnreads\": 5, \n \"isFavorites\": 1, \n \"isUnsigned\": 29, \n \"result\": {\"multicast_id\":6412587390025910550,\n            \"success\":1,\n            \"failure\":0,\n            \"canonical_ids\":0,\n            \"results\":[{\"message_id\":\"0:1448809187076391%a96836d8f9fd7ecd\"}]\n            }\n }",
          "type": "json"
        }
      ]
    },
    "filename": "./apidoc.php",
    "groupTitle": "Surat"
  },
  {
    "type": "get",
    "url": "/surats/:token/:offset/:limit",
    "title": "Mendapatkan Seluruh Surat Masuk",
    "version": "0.1.0",
    "name": "GetSurat",
    "group": "Surat",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "token",
            "description": "<p>Users unique token.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>Integer</p> ",
            "optional": false,
            "field": "offset",
            "description": "<p>Offset data.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>Integer</p> ",
            "optional": false,
            "field": "limit",
            "description": "<p>Limit data.</p> "
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\"count\": 1, \"isUnreads\": 1, \"isFavorites\": 0, \"isUnsigned\": 30, \n \"result\": [{\n     \"pengirim\":\"PUSTIKOM\",\n     \"id\":\"6\",\n     \"hal\":\"Akreditasi\", \n     \"subject\":\"Contoh submit surat\",\n     \"role\":\"003000001\",\n     \"notif_web\":false,\n     \"notif_app\":false,\n     \"isFavorite\":false,\n     \"isUnread\":true,\n     \"no_surat\":\"3\\/UN39.18\\/AK\\/15\",\n     \"lampiran\":\"0\",\n     \"namaPenandatangan\":\"M. Ficky Duskarnaen\",\n     \"jabatanPenandatangan\":\"Kepala UPT Pusat Komputer\",\n     \"tanggal\":\"03 November 2015\",\n     \"isi\":\"<p>Contoh response yang berhasil<\\/p>\",\n     \"tembusan\":\"Kepala UPT Pusat Komputer\",\n     \"file_lampiran\":[],\n     \"isUploaded\":false,\n     \"uploadedFilePath\":\"assets\\/uploaded\\/283_META MODEL NLP revisi.pdf\"}]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./apidoc.php",
    "groupTitle": "Surat"
  },
  {
    "type": "get",
    "url": "/favorites/:token/:offset/:limit",
    "title": "Mendapatkan Seluruh Surat Favorite",
    "version": "0.1.0",
    "name": "GetSuratFavorite",
    "group": "Surat",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "token",
            "description": "<p>Users unique token.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>Integer</p> ",
            "optional": false,
            "field": "offset",
            "description": "<p>Offset data.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>Integer</p> ",
            "optional": false,
            "field": "limit",
            "description": "<p>Limit data.</p> "
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\"count\": 1, \"isUnreads\": 1, \"isFavorites\": 0, \"isUnsigned\": 30, \n \"result\": [{\n     \"pengirim\":\"PUSTIKOM\",\n     \"id\":\"6\",\n     \"hal\":\"Akreditasi\", \n     \"subject\":\"Contoh submit surat\",\n     \"role\":\"003000001\",\n     \"notif_web\":false,\n     \"notif_app\":false,\n     \"isFavorite\":false,\n     \"isUnread\":true,\n     \"no_surat\":\"3\\/UN39.18\\/AK\\/15\",\n     \"lampiran\":\"0\",\n     \"namaPenandatangan\":\"M. Ficky Duskarnaen\",\n     \"jabatanPenandatangan\":\"Kepala UPT Pusat Komputer\",\n     \"tanggal\":\"03 November 2015\",\n     \"isi\":\"<p>Contoh response yang berhasil<\\/p>\",\n     \"tembusan\":\"Kepala UPT Pusat Komputer\",\n     \"file_lampiran\":[],\n     \"isUploaded\":false,\n     \"uploadedFilePath\":\"assets\\/uploaded\\/283_META MODEL NLP revisi.pdf\"}]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./apidoc.php",
    "groupTitle": "Surat"
  },
  {
    "type": "get",
    "url": "/suratsKeluar/:token/:offset/:limit",
    "title": "Mendapatkan Seluruh Surat Keluar",
    "version": "0.1.0",
    "name": "GetSuratKeluar",
    "group": "Surat",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "token",
            "description": "<p>Users unique token.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>Integer</p> ",
            "optional": false,
            "field": "offset",
            "description": "<p>Offset data.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>Integer</p> ",
            "optional": false,
            "field": "limit",
            "description": "<p>Limit data.</p> "
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\"count\": 1, \"isUnreads\": 1, \"isFavorites\": 0, \"isUnsigned\": 30, \n \"result\": [{\n     \"pengirim\":\"PUSTIKOM\",\n     \"id\":\"6\",\n     \"hal\":\"Akreditasi\", \n     \"subject\":\"Contoh submit surat\",\n     \"role\":\"003000001\",\n     \"notif_web\":false,\n     \"notif_app\":false,\n     \"isFavorite\":false,\n     \"isUnread\":true,\n     \"no_surat\":\"3\\/UN39.18\\/AK\\/15\",\n     \"lampiran\":\"0\",\n     \"namaPenandatangan\":\"M. Ficky Duskarnaen\",\n     \"jabatanPenandatangan\":\"Kepala UPT Pusat Komputer\",\n     \"tanggal\":\"03 November 2015\",\n     \"isi\":\"<p>Contoh response yang berhasil<\\/p>\",\n     \"tembusan\":\"Kepala UPT Pusat Komputer\",\n     \"file_lampiran\":[],\n     \"isUploaded\":false,\n     \"uploadedFilePath\":\"assets\\/uploaded\\/283_META MODEL NLP revisi.pdf\"}]\n}",
          "type": "json"
        }
      ]
    },
    "filename": "./apidoc.php",
    "groupTitle": "Surat"
  },
  {
    "type": "put",
    "url": "/koreksiSurat",
    "title": "Koreksi Surat",
    "version": "0.1.0",
    "name": "KoreksiSurat",
    "group": "Surat",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "token",
            "description": "<p>User's Token.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "pesan",
            "description": "<p>User's Pesan.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>Integer</p> ",
            "optional": false,
            "field": "id_surat",
            "description": "<p>Surat's ID.</p> "
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\"result\": \"Success\", \n \"account\": \"003000\", \n \"isCorrected\": 4\n }",
          "type": "json"
        }
      ]
    },
    "filename": "./apidoc.php",
    "groupTitle": "Surat"
  },
  {
    "type": "put",
    "url": "/setFavorite",
    "title": "Set Favorite Surat",
    "version": "0.1.0",
    "name": "SetFavoriteSurat",
    "group": "Surat",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "token",
            "description": "<p>User's Token.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>Boolean</p> ",
            "optional": false,
            "field": "status",
            "description": "<p>Surat's Favorite Status.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>Integer</p> ",
            "optional": false,
            "field": "id_surat",
            "description": "<p>Surat's ID.</p> "
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\"isFavorites\": 2, \n \"result\": {\"status\":true}\n }",
          "type": "json"
        }
      ]
    },
    "filename": "./apidoc.php",
    "groupTitle": "Surat"
  },
  {
    "type": "put",
    "url": "/setRead",
    "title": "Set Read Surat",
    "version": "0.1.0",
    "name": "SetReadSurat",
    "group": "Surat",
    "parameter": {
      "fields": {
        "Parameter": [
          {
            "group": "Parameter",
            "type": "<p>String</p> ",
            "optional": false,
            "field": "token",
            "description": "<p>User's Token.</p> "
          },
          {
            "group": "Parameter",
            "type": "<p>Integer</p> ",
            "optional": false,
            "field": "id_surat",
            "description": "<p>Surat's ID.</p> "
          }
        ]
      }
    },
    "success": {
      "examples": [
        {
          "title": "Success-Response:",
          "content": "HTTP/1.1 200 OK\n{\"isUnreads\": 5, \"result\": {\"status\":true}}",
          "type": "json"
        }
      ]
    },
    "filename": "./apidoc.php",
    "groupTitle": "Surat"
  }
] });