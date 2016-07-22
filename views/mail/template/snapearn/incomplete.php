<tbody>
    <tr>
    <td colspan="2"><h1 style="font-weight: 100; font-size: 30px; line-height: 60px; margin: 20px 0 0;">Hi&nbsp;<?= $params[0][1] ?></h1></td>
    <td width="100" style="display: table-cell; overflow: hidden;" rowspan="3">&nbsp;</td>
    </tr>
    <tr>
        <td width="50">&nbsp;</td>
        <td>
            <p>We were unable to process your claim as the uploaded photo of your receipt
                was incomplete Please snap another photo of your receipt and re-upload.
            </p><br/>
            <p>Ensure that these details are clearly displayed in the photo of your receipt:</p><br/>
            <ol>
                <li>Date &amp; Time of Receipt</li>
                <li>Merchant Name</li>
                <li>Transaction Amount</li>
                <li>Receipt Number</li>
                <li>Merchant address/ location</li>
            </ol>
            <p>Below is a sample photo as a guideline.</p><br/>
            <img src="<?= $params[1][1] ?>" width="70%" /><br/>
            <p>
                Best Regards,<br/>
                getmanis.com team
            </p>
        </td>
    </tr>
    <tr>
        <td height="50">&nbsp;</td>
        <td>&nbsp;</td>
    </tr>
</tbody>
