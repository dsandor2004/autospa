global LoopUntil = 1

#Include params.dll

if LoopUntil = 1
	LoopUntil22()

StartAllLabel:
StartAll()

if LoopUntil = 1
{
	Loop {
		Sleep 1000
		myTime := getMyTime()

		if (myTime >= 2100)
			break
	}
}

Random, rand, 1, 5
lockRandom := 1
LastSuccessfulBetTime := A_TickCount
ruletteRestartedCount := 0
LastRestart := A_TickCount

Loop {
	myTime := getMyTime()
	if (LoopUntil = 1 && (myTime < 2059 || myTime > 2358))
		break

	;restart if needed
	If ProcessExist("FullTiltPokerEU.exe")
	{
		;
	
	} else
	{
		Sleep 5000
		Goto, StartAllLabel
		return
	}

	WinActivate, Live
	Sleep 500
	;close error dialog if exists
	PixelGetColor, color, %ErrorX%, %ErrorY%
	if (color = ErrorDialogColor) {		
		Click %ErrorDialogOkX%, %ErrorDialogOkY%
	}

	if (lockRandom == 0)
		Random, rand, 1, 5

	PX := MouseX[rand]
	PY := MouseY[rand]
	BX := BetX[rand]
	BY := BetY[rand]
	PixelGetColor, color, %PX%, %PY%

	if (color != "0xFEFEFE") {
		Send {Enter} ;needed to handle popup dialogs

		Click %BX%, %BY%
		MouseMove, 200, 200
		Sleep 800
		PixelGetColor, color, %PX%, %PY%
		lockRandom := 0
		
		if (color = "0xFEFEFE")
		{
			lockRandom := 1
			LastSuccessfulBetTime := A_TickCount
		}
	}

	ElapsedTime := A_TickCount - LastSuccessfulBetTime
	if (ElapsedTime > 100000)
	{
		ElapsedRestartTime := A_TickCount - LastRestart

		if (ElapsedRestartTime < 450000)
		{
			LastSuccessfulBetTime := A_TickCount
			LastRestart := A_TickCount
			ruletteRestartedCount := ruletteRestartedCount + 1
			if (ruletteRestartedCount == 3)
			{				
				ruletteRestartedCount := 0
				WinClose, Full Tilt Poker.eu
			} else
			{
				WinClose, Live
				Sleep 5000
				openRoulette()
			}

		}

	}
	Sleep 8000

}

Sleep 100000
if (A_TimeIdle > 100000) {
	Shutdown, 1
	return
}


ProcessExist(Name){
	Process,Exist,%Name%
	return Errorlevel
}

getMyTime()
{
	if (A_Hour == 00) {
		hour = 24
	} else {	
		hour := A_Hour
	}

	myTime = %hour%%A_Min%

	return myTime
}

LoopUntil22()
{
	Loop {
		Sleep 1000
		myTime := getMyTime()

		if (myTime >= 2058)
			break

	}
}

openRoulette()
{
	;wait until Live Roulette is clickable
	Loop
	{
		Send {Esc} ;to handle promotion popups
		Sleep 8000
		WinActivate, "Full Tilt Poker.eu"
		Sleep 200
		MouseMove, LiveRouletteX, LiveRouletteY
		Sleep 200
		PixelGetColor, color, %LiveRouletteX%, %LiveRouletteY%

		if (color = "0xFFFFFF")
			break
	}

	Send {Esc} ;to handle promotion popups
	Sleep 50
	WinActivate, "Full Tilt Poker.eu"
	Click LiveRouletteX, LiveRouletteY ;Live Roulette click
	Sleep 1000
	WinActivate, Live

	;wait until Tables appear
	waitTime := 1
	Loop
	{
		Sleep 1000
		waitTime := waitTime + 1
		if (waitTime = 50)
		{
			WinClose, Live
			openRoulette()
			return
		}

		WinActivate, Live
		PixelGetColor, color, %TablesX%, %TablesY%
		if (color = TablesColor)
			break
	}

	Click %LiveRouletteIconX%, %LiveRouletteIconY% ;Full Tilt Roulette click

	;wait until Video Loads
	Loop
	{
		Sleep 1000
		WinActivate, Live
		PixelGetColor, color, %LiveVideoX%, %LiveVideoY%
		if (color = LiveVideoColor || color = "0x878787")
			break
	}

	;User already at the table after window reopen - click "Yes" on warning window
	PixelGetColor, color, %UserAlreadyX%, %UserAlreadyY%
	if (color == UserAlreadyColor)
	{
		Click %UserAlreadyOkX%, %UserAlreadyOkY%
	}

	Click %ClassicViewX%, %ClassicViewY% ;Classic View click
	Sleep 1000
	Click %MuteX%, %MuteY% ;Mute sound
	Click %FavouriteX%, %FavouriteY% ;Open Favourite Bets
}

StartAll()
{
	;MouseGetPos x,y
	;MsgBox %x% %y%
	;return

	Run %FTP%
	Sleep 3000

	;wait until Password input appears
	SoftwareUpdate := 0
	Loop
	{
		Sleep 1000
		WinActivate, "Full Tilt Poker.eu"
		WinGetPos, X, Y, W, Height, A
		if(WinExist("Full Tilt Poker.eu"))
		{
			if (Height > 500)
			{
				PixelGetColor, color, %PasswordX%,%PasswordY%
				if (color = "0xFFFFFF")
					break
			} else
			{
				if (Height < 300 && SoftwareUpdate = 0)
				{
					Send {Enter}
					SoftwareUpdate := 1
				}
			}
		}
	}
	
	Sleep 2000
	Send, %Password%
	Sleep 300
	Click %LoginX%, %LoginY% ;Login button click
	Sleep 1000

	openRoulette()
}