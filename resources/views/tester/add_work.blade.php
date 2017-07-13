<html>
<body>
    <div>
        <form method="post" action="{{route('random.store')}}">
            {{csrf_field()}}
            <div>
                <label for="date" >วันที่</label>
                <input value="2017-07-09" name="date" type="text" id="date">
            </div>
            <div>
                <label for="time_period" >เวลา</label>
                <input value="16:00 - 17:00" name="time_period" type="text" id="time_period">
            </div>
            <div>
                <button type="submit">บันทึก</button>
            </div>
        </form>
    </div>
</body>
</html>