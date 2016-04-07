package com.ebizu.epay;

import java.io.File;
import java.io.InputStream;
import java.io.OutputStream;
import java.io.PrintStream;

public class Epay
{
  public static void main(String[] args)
  {
    StringBuilder payload = new StringBuilder(new StringBuilder().append("<?xml version=\"1.0\" encoding=\"UTF-8\"?><soapenv:Envelope xmlns:soapenv=\"http://schemas.xmlsoap.org/soap/envelope/\" xmlns:xsd=\"http://www.w3.org/2001/XMLSchema\" xmlns:xsi=\"http://www.w3.org/2001/XMLSchema-instance\"><soapenv:Body><ns1:").append(args[2]).append(" soapenv:encodingStyle=\"http://schemas.xmlsoap.org/soap/encoding/\" xmlns:ns1=\"urn:EPAYIBWS\">").append("<in0 xsi:type=\"ns2:RequestMsg\" xmlns:ns2=\"urn:oglws\">").toString());
    try
    {
      for (int i = 4; i < args.length; i++) {
        String[] s = args[i].split("=");
        if ((s[1] != null) && (!s[1].isEmpty()))
          payload.append(new StringBuilder().append("<").append(s[0]).append(">").append(s[1]).append("</").append(s[0]).append(">").toString());
      }
    }
    catch (Exception e)
    {
    }
    payload.append(new StringBuilder().append("</in0></ns1:").append(args[2]).append(">").append("</soapenv:Body>").append("</soapenv:Envelope>\n\n").toString());

    String header = new StringBuilder().append("POST ").append(args[1]).append(" HTTP/1.1\n").append("Host: epay.ebizu.com\n").append("SOAPAction: ").append(args[2]).append("\n").append("Content-Length: ")
      .append(payload
      .length()).append("\n\n").toString();
    try {
      File f = new File(".");
      String cmd = new StringBuilder().append("/usr/bin/openssl s_client -connect ").append(args[0]).append(" -cert ").append(args[3]).append("cert.pem -key ").append(args[3]).append("private.pem").toString();
      Process process = Runtime.getRuntime().exec(cmd);
      InputStream is = process.getInputStream();
      OutputStream os = process.getOutputStream();
      byte[] b = new byte[256];
      int l = 0;
      StringBuilder response = new StringBuilder();
      while ((l = is.read(b)) > 0) {
        response.append(new String(b, 0, l));
        String s = response.toString();
        if ((s.contains("SSL-Session")) && (s.trim().endsWith("---"))) {
          break;
        }
      }
      os.write(header.getBytes());
      os.write(payload.toString().getBytes());
      os.flush();
      response = new StringBuilder();
      while ((l = is.read(b)) > 0) {
        response.append(new String(b, 0, l));
        String s = response.toString();
        if ((s.contains("<?xml")) && (s.contains("</soapenv:Envelope>"))) {
          break;
        }
      }
      is.close();
      os.close();
      String xml = new StringBuilder().append("").append(response.subSequence(response.indexOf("<?xml"), response.indexOf("</soapenv:Envelope>") + 19)).toString();
      System.out.println(xml);
    } catch (Exception e) {
      e.printStackTrace();
    }
  }
}