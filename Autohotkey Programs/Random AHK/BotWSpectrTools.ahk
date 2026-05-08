CoordMode, Pixel, Screen

;=========== SKILL DELAY CONFIG
dKill = 400
dScreen = 1500
dConcentration = 1000
dWindWalker = 1500
dHeal = 10
dHealer = 500
dChat = 500
dEnter = 3
dDelay = 10000  ; Delay en milisegundos de el tiempo que estara en mapa
color = 0xFF31FF
colorNPC = 0xFF0066

dStorage = 10
items = 5
;==============================

Gui, Font, S8 CDefault, Consolas

Gui, Add, GroupBox, x12 y9 w210 h240 , Sniper Config
Gui, Add, Text, x22 y29 w50 h20 +Right, WinID:
Gui, Add, Edit, vWID1 x82 y29 w60 h20 +ReadOnly,
Gui, Add, Button, gSet1 x152 y29 w40 h20 , Set
Gui, Add, GroupBox, x22 y59 w190 h180 , Skills:

Gui, Add, Text, x32 y79 w40 h30 , Double Strafe:
Gui, Add, DropDownList, vSS1 x82 y79 w40, F1||F2|F3|F4|F5|F6|F7|F8|F9
Gui, Add, CheckBox, vDoubleStrafe x132 y79 w70 h20, Enable

Gui, Add, Text, x32 y119 w40 h30 , Improve Concentration:
Gui, Add, DropDownList, vSS3 x82 y119 w40, F1|F2|F3|F4||F5|F6|F7|F8|F9|
Gui, Add, CheckBox, vConcentration x132 y119 w70 h20, Enable

Gui, Add, Text, x32 y159 w40 h30 , Wind Walker:
Gui, Add, DropDownList, vSS4 x82 y159 w40, F1|F2|F3|F4|F5|F6|F7|F8||F9
Gui, Add, CheckBox, vWindWalker x132 y159 w70 h20, Enable

Gui, Add, Text, x32 y199 w40 h30 , Fly Wing:
Gui, Add, DropDownList, vFlyWing x82 y199 w40, F1|F2||F3|F4|F5|F6|F7|F8|F9
Gui, Add, Text, x132 y199 h20, delay:
Gui, Add, Edit, vDelay x172 y196 h20, 400

Gui, Add, Text, x12 y260 w60 h20, @go alt +
Gui, Add, Edit, vGo x72 y257 w20 h20, 1
Gui, Add, Text, x112 y260 w90 h20, @storage alt +
Gui, Add, Edit, vStorage x202 y257 w20 h20, 2

Gui, Add, Button, gHealer x12 y290 w60 h20 , Healer
Gui, Add, Button, gWarper x12 y320 w60 h20 , Warper

Gui, Add, Button, gInventory x87 y290 w60 h20 , Inventory
Gui, Add, Button, gStorage x87 y320 w60 h20 , Storage

Gui, Add, Button, gStart x162 y290 w60 h20 , Start
Gui, Add, Button, gClose x162 y320 w60 h20 , ExitApp

Gui, Show, xCenter yCenter w234 h360, Bot
Return

Set1:
{
	Gui, Hide
	KeyWait, Space, D
	MyWinID := WinExist("A")
	GuiControl,, WID1, %MyWinID%
	WinGetPos, x, y, w, h, %WID1%
	Gui, Show
	Return
}

Healer:
{
	Gui, Hide
	KeyWait, Space, D
	MouseGetPos, Hx, Hy
	Gui, Show
	Return
}

Warper:
{
	Gui, Hide
	KeyWait, Space, D
	MouseGetPos, Wx, Wy
	Gui, Show
	Return
}

Storage:
{
	Gui, Hide
	KeyWait, Space, D
	MouseGetPos, Sx, Sy
	Gui, Show
	Return
}

Inventory:
{
	Gui, Hide
	KeyWait, Space, D
	MouseGetPos, Ix, Iy
	Gui, Show
	Return
}

Start:
{
	Gui, Submit,
	
	WinActivate, ahk_id %WID1%
	Sleep, 50
	
	Loop,
	{
		Loop, %dStorage%
		{
			;heal
			MouseClick, left, Hx, Hy
			Sleep, %dHealer%
			
			;skills
			if(Concentration == 1)
			{
				Send, {%SS3%}
				Sleep, %dConcentration%
			}
			
			if(WindWalker == 1)
			{
				Send, {%SS4%}
				Sleep, %dWindWalker%
			}
			
			;heal
			Loop, %dHeal%
			{
			MouseClick, left, Hx, Hy
			Sleep, %dHealer%
			
			;warp
			MouseClick, left, Wx, Wy
			Sleep, %dChat%
			
			Loop, %dEnter%
			{
				Send, {Enter}
				Sleep, %dChat%
			}
			Sleep, %dScreen%
			
				Send, {F1 down}  
				Sleep, 1500 
				Send, {F1 up}
				Sleep, %dDelay%
				Send, {F1 down}  
				Sleep, 1500
				Send, {F1 up}
	
			Sleep, %dKill%
				
			
			
			
			;@go
			Send, {Alt down}
			Sleep, 100
			Send, {%Go%}
			Sleep, 100
			Send, {Alt up}
			Sleep, %dScreen%
		}
		
		;open inventory
		Send, {Alt down}
		Sleep, 100
		Send, {e}
		Sleep, 100
		Send, {Alt up}
		
		;open storage
		Send, {Alt down}
		Sleep, 100
		Send, {%Storage%}
		Sleep, 100
		Send, {Alt up}
		
		;store items
		Loop, %items%
		{
			MouseClickDrag, left, Ix, Iy, Sx, Sy, 5
			Sleep, 50
			Send, {Enter}
			Sleep, 50
		}
		
		;close inventory
		Send, {Alt down}
		Sleep, 100
		Send, {e}
		Sleep, 100
		Send, {Alt up}
		
		;@go close storage
		Send, {Alt down}
		Sleep, 100
		Send, {%Go%}
		Sleep, 100
		Send, {Alt up}
		
		Sleep, %dScreen%
	}
}
}

Close:
{
	ExitApp
}

GuiClose:
{
	ExitApp
}

End::Pause, Toggle, 1