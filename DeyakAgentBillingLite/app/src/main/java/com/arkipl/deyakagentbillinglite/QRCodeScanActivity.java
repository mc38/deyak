package com.arkipl.deyakagentbillinglite;

import android.app.Activity;
import android.content.Context;
import android.content.DialogInterface;
import android.content.Intent;
import android.database.Cursor;
import android.os.Bundle;
import android.support.v7.app.AlertDialog;
import android.support.v7.app.AppCompatActivity;
import android.view.Menu;
import android.view.MenuItem;

import com.google.zxing.Result;

import in.deyak.supporting.CommonFunction;
import in.deyak.supporting.DatabaseOperation;
import in.deyak.supporting.Rcrypt;
import in.deyak.supporting.TableData;
import me.dm7.barcodescanner.zxing.ZXingScannerView;


public class QRCodeScanActivity extends AppCompatActivity implements ZXingScannerView.ResultHandler {

    private ZXingScannerView mScannerView;
    CommonFunction cf = new CommonFunction();
    Context ctx = this;
    Activity act = this;

    String type;

    @Override
    protected void onCreate(Bundle savedInstanceState) {
        super.onCreate(savedInstanceState);
        setContentView(R.layout.activity_qrcode_scan);

        Intent intnt = this.getIntent();
        type = intnt.getStringExtra("t");

        QrScanner();

    }

    public void QrScanner(){

        mScannerView = new ZXingScannerView(this);   // Programmatically initialize the scanner view
        setContentView(mScannerView);

        mScannerView.setResultHandler(this); // Register ourselves as a handler for scan results.
        mScannerView.startCamera();         // Start camera

    }

    @Override
    public void onPause() {
        super.onPause();
        mScannerView.stopCamera();           // Stop camera on pause
    }

    @Override
    public void handleResult(Result result) {
        String code = result.getText();

        if(type.equals("0")) {

            DatabaseOperation dbo = new DatabaseOperation(ctx);
            String query = "SELECT " + TableData.TableInfo.TABLE_MDATA_id + "," + TableData.TableInfo.TABLE_MDATA_cid + "," + TableData.TableInfo.TABLE_MDATA_consumer_name + "," + TableData.TableInfo.TABLE_MDATA_consumer_address + "," + TableData.TableInfo.TABLE_MDATA_survey + "," + TableData.TableInfo.TABLE_MDATA_n_status + " FROM " + TableData.TableInfo.TABLE_MDATA_NAME + " WHERE " + TableData.TableInfo.TABLE_MDATA_qrcode + "='" + code + "'";
            Cursor CR = dbo.selectsqlInformation(dbo, query);
            if (CR.getCount() > 0) {
                CR.moveToFirst();

                final String conid = CR.getString(0);
                String cid = Rcrypt.decode(DatabaseOperation.key, CR.getString(1));
                String name = Rcrypt.decode(DatabaseOperation.key, CR.getString(2));
                String address = Rcrypt.decode(DatabaseOperation.key, CR.getString(3));
                String surv = Rcrypt.decode(DatabaseOperation.key, CR.getString(4));
                int survey = Integer.valueOf(surv);
                String condetails = name + "\n" + address + "\n" + cid;

                if (!CR.isNull(5) && !CR.getString(5).isEmpty()) {
                    new AlertDialog.Builder(ctx)
                            .setIcon(R.drawable.ic_action_notification)
                            .setTitle("Notification")
                            .setMessage("Billing already done")
                            .setPositiveButton("Re-Print Bill", new DialogInterface.OnClickListener() {
                                @Override
                                public void onClick(DialogInterface dialog, int which) {
                                    dialog.cancel();
                                    dialog.dismiss();

                                    Intent billprintintent = new Intent(act, BillPrintActivity.class);
                                    billprintintent.putExtra("id", conid);
                                    billprintintent.putExtra("t", "1");
                                    act.finish();
                                    startActivity(billprintintent);
                                }
                            })
                            .setNegativeButton("Home", new DialogInterface.OnClickListener() {
                                @Override
                                public void onClick(DialogInterface dialog, int which) {
                                    dialog.cancel();
                                    dialog.dismiss();

                                    Intent home = new Intent(ctx, HomeActivity.class);
                                    act.finish();
                                    startActivity(home);
                                }
                            })
                            .setCancelable(false)
                            .show();
                } else {
                    Intent gointent = null;

                    if (survey == 0) {
                        gointent = new Intent(act, SurveyActivity.class);
                        gointent.putExtra("id", conid);
                    } else {
                        gointent = new Intent(act, ReadingActivity.class);
                        gointent.putExtra("id", conid);
                    }
                    final Intent finalGointent = gointent;
                    new AlertDialog.Builder(ctx)
                            .setIcon(R.drawable.ic_action_notification)
                            .setTitle("Consumer Details")
                            .setMessage(condetails)
                            .setPositiveButton("Go", new DialogInterface.OnClickListener() {
                                @Override
                                public void onClick(DialogInterface dialog, int which) {
                                    dialog.cancel();
                                    dialog.dismiss();
                                    act.finish();
                                    startActivity(finalGointent);
                                }
                            })
                            .setNegativeButton("Home", new DialogInterface.OnClickListener() {
                                @Override
                                public void onClick(DialogInterface dialog, int which) {
                                    dialog.cancel();
                                    dialog.dismiss();

                                    Intent home = new Intent(ctx, HomeActivity.class);
                                    act.finish();
                                    startActivity(home);
                                }
                            })
                            .setCancelable(false)
                            .show();
                }

            } else {
                new AlertDialog.Builder(ctx)
                        .setIcon(R.drawable.ic_action_notification)
                        .setTitle("Notification")
                        .setMessage("No consumer found or Invalid QR Code")
                        .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                            @Override
                            public void onClick(DialogInterface dialog, int which) {
                                dialog.cancel();
                                dialog.dismiss();

                                Intent home = new Intent(ctx, HomeActivity.class);
                                act.finish();
                                startActivity(home);
                            }
                        })
                        .setCancelable(false)
                        .show();
            }
        }else if(type.equals("1")){
            Intent paymentintent = new Intent(act, PaymentActivity.class);
            paymentintent.putExtra("qrc", code);
            act.finish();
            startActivity(paymentintent);
        }else{
            new AlertDialog.Builder(ctx)
                    .setIcon(R.drawable.ic_action_notification)
                    .setTitle("Notification")
                    .setMessage("Data error.")
                    .setPositiveButton("OK", new DialogInterface.OnClickListener() {
                        @Override
                        public void onClick(DialogInterface dialog, int which) {
                            dialog.cancel();
                            dialog.dismiss();

                            Intent home = new Intent(ctx, HomeActivity.class);
                            act.finish();
                            startActivity(home);
                        }
                    })
                    .setCancelable(false)
                    .show();
        }


    }



    ////////////////////////////////////////////////////////////////////////////////////////////////

    @Override
    public void onBackPressed() {
        CommonFunction.makeToast(ctx,"Tap cross to close").show();
    }

    @Override
    public boolean onCreateOptionsMenu(Menu menu) {

        // Inflate the menu; this adds items to the action bar if it is present.
        getMenuInflater().inflate(R.menu.menu_qrc, menu);
        return true;
    }

    @Override
    public boolean onOptionsItemSelected(MenuItem item) {
        // Handle action bar item clicks here. The action bar will
        // automatically handle clicks on the Home/Up button, so long
        // as you specify a parent activity in AndroidManifest.xml.
        int id = item.getItemId();
        if (id == R.id.action_close) {
            Intent home = new Intent(ctx, HomeActivity.class);
            this.finish();
            startActivity(home);
            return true;
        }
        return super.onOptionsItemSelected(item);
    }

    @Override
    public void onDestroy() {
        super.onDestroy();
        Runtime.getRuntime().gc();
    }
}
