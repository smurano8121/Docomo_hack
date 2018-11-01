package com.example.usk.piccomm

import android.content.Intent
import android.net.Uri
import android.support.v7.app.AppCompatActivity
import android.os.Bundle

class MainActivity : AppCompatActivity() {
    override fun onCreate(savedInstanceState: Bundle?) {
        super.onCreate(savedInstanceState)
        setContentView(R.layout.activity_main)
        val uri :Uri= Uri.parse("http://IPアドレス/Public/upload.html")//サインイン・アカウント登録用URL
        val intent = Intent(Intent.ACTION_VIEW,uri)
        startActivity(intent)
    }
}
