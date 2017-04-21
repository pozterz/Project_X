<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <meta http-equiv="X-UA-Compatible" content="IE=edge">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="_token" content="{{ csrf_token() }}"/>
  <title>Queue System</title>
</head>

          <h2>สำเร็จ !!</h2>
          <p>ขอบคุณ คุณ {!! Auth::user()->name !!} สำหรับการจอง</p>
          <p>รายละเอียดคิวของคุณคือ</p>
          <ul>
            <li>{!! $queue->name !!}</li>
            <li>{!! $queue->queueType->name !!}</li>
            <li>เคาน์เตอร์ที่ : {!! $queue->user->counter_id !!} | {!! $queue->user->name !!}</li>
            <li>เวลา : {!! $data->time->format('d/m/Y H:i:s') !!}</li>
            <li>{!! $data->reserved_min !!} นาที</li>
          </ul>

          <h3>รหัสยืนยันของคุณคือ</h3>
          <div>
            <strong>{!! $data->captcha !!}</strong>
          </div>

          <p>เราได้ทำการจัดส่ง รหัสการจองไปให้คุณทาง Email เรียบร้อยแล้ว</p>
          <p>กรุณานำรหัสการจอง ไปใช้ยืนยันที่ธนาคารสาขาที่ท่านได้ทำการจองไว้ กรุณาไปก่อนเวลา 10-15 นาที</p>

        </div>
      </div>
      </div>
    </div>
  </div>

  </div>

</body>
</html>
