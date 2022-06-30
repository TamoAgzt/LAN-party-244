local RS = game:GetService("RunService")
local Debris = game:GetService("Debris")

-- Required objects --
local Gun = script.Parent
local Handle = Gun.Handle
local Mag = Gun.Mag
local FastCast = require(Gun.FastCastRedux)

local FireEvent = Gun:WaitForChild("FireEvent")
local ReloadEvent = Gun:WaitForChild("ReloadEvent")
local DownEvent = Gun:WaitForChild("GunDown")
local RecoilEvent = Gun:WaitForChild("RecoilEvent")

local GunMuzzle = Gun.Handle.Muzzle
local MuzzleLight = Handle.Muzzle.Flash
local FlashParticles = GunMuzzle.Muzzle

local BulletTypes = game.ReplicatedStorage.Miscs.Projectiles

local AmmoGui = Handle.AmmoGui
local Hitmarker = BulletTypes["HitMarker"]
local EmptyCasing = Handle.EmptyCase
local EjectPort = Handle.EjectPort
local CasingsFolder = game.Workspace.Casings
local SoundsFolder = GunMuzzle.Sounds


-- Get the settings
local Settings = require(Gun.Settings)

local FireDelay = 60 / Settings.RPM


-- Folder to store bullets
local BulletFolder = workspace:FindFirstChild("BulletsFolder") or Instance.new("Folder", workspace)
BulletFolder.Name = "BulletsFolder"


-- The bullet which is displayed
local BulletTemplate = BulletTypes[Settings.BulletType]

-- Visualize bullet trajectory
FastCast.VisualizeCasts = Settings.DebugMode

-- Create gun's caster
local Caster = FastCast.new()

-- Define the raycastparams
local CastParams = RaycastParams.new()
CastParams.FilterType = Enum.RaycastFilterType.Blacklist
CastParams.IgnoreWater = true

-- Define the cast behavior
local CastBehavior = FastCast.newBehavior()
CastBehavior.RaycastParams = CastParams
CastBehavior.Acceleration = Vector3.new(0, -Settings.Gravity, 0)
CastBehavior.AutoIgnoreContainer = false
CastBehavior.CosmeticBulletContainer = BulletFolder
CastBehavior.CosmeticBulletTemplate = BulletTemplate


-- Create animations --
local IdleAnim
local IdleAnimTrack
if Settings.HoldingAnim ~= nil then

	IdleAnim = Instance.new("Animation")
	IdleAnim.Name = "IdleAnim"
	IdleAnim.Parent = script.Parent
	IdleAnim.AnimationId = Settings.HoldingAnim
end

local ReloadAnim
local ReloadAnimTrack
if Settings.ReloadAnim ~= nil then

	ReloadAnim = Instance.new("Animation")
	ReloadAnim.Name = "ReloadAnim"
	ReloadAnim.Parent = script.Parent
	ReloadAnim.AnimationId = Settings.ReloadAnim
end

local DownAnim
local DownAnimTrack
if Settings.GunDownAnim ~= nil then

	DownAnim = Instance.new("Animation")
	DownAnim.Name = "DownAnim"
	DownAnim.Parent = script.Parent
	DownAnim.AnimationId = Settings.GunDownAnim
end


-- When tool is equipped
local function onEquipped()

	-- Raycast blacklist
	CastParams.FilterDescendantsInstances = {Gun, Gun.Parent, BulletFolder}
	
	-- Get player
	local PlayerName = script.Parent.Parent.Name
	local PlayerHuman = game.Workspace[PlayerName].Humanoid
	local Player = game.Players[PlayerName]
	local PlayerGuis = Player.PlayerGui

	-- Set gun's owner
	Settings.Owner = PlayerName
	
	-- Load animations
	if Settings.HoldingAnim ~= nil then

		IdleAnimTrack = PlayerHuman:LoadAnimation(IdleAnim)
		IdleAnimTrack.Name = "IdleAnim"
	end
	
	if Settings.ReloadAnim ~= nil then

		ReloadAnimTrack = PlayerHuman:LoadAnimation(ReloadAnim)
		ReloadAnimTrack.Name = "ReloadAnim"
	end
	
	if Settings.GunDownAnim ~= nil then

		DownAnimTrack = PlayerHuman:LoadAnimation(DownAnim)
		DownAnimTrack.Name = "DownAnim"
	end

	-- Play idle animation
	if Settings.GunDownAnim ~= nil and Settings.GunDown == true then

		DownAnimTrack:Play()

	elseif Settings.HoldingAnim ~= nil and Settings.GunDown == false then

		IdleAnimTrack:Play()
	end
	
	-- Give ammo gui to player
	local GuiClone = AmmoGui:Clone()
	GuiClone.Name = "AmmoGui"
	GuiClone.Parent = PlayerGuis
	
	-- Reduce player's walkspeed
	if Settings.ReduceWalkspeed == true then

		PlayerHuman.WalkSpeed -= Settings.WalkspeedReduction
	end
	
	-- Update ammo counter
	UpdateAmmo(Player)
end


-- When tool is unequipped
local function onUnequipped()

	-- Remove ammo Gui from player
	local PlayerName = script.Parent.Parent.Parent.Name
	local PlayerHuman = game.Workspace[PlayerName].Humanoid
	local PlayerGuis = game.Players[PlayerName].PlayerGui
	local RemoveGui = PlayerGuis["AmmoGui"]
	RemoveGui:Destroy()

	-- Revert walkspeed reduction
	if Settings.ReduceWalkspeed == true then

		PlayerHuman.WalkSpeed += Settings.WalkspeedReduction
	end

	if Settings.HoldingAnim ~= nil and Settings.GunDown == false then

		IdleAnimTrack:Stop()
	end
	
	if Settings.GunDownAnim ~= nil and Settings.GunDown == true then
		
		DownAnimTrack:Stop()
	end
end


-- When raycast hits something
local function OnRayHit(cast, result, velocity, bullet)

	-- The instance which was hit
	local hit = result.Instance

	if Settings.Explosive == true then

		-- Create explosion
		local Explosion = Instance.new("Explosion")
		Explosion.Parent = workspace
		Explosion.Position = result.Position
		Explosion.BlastPressure = Settings.BlastPressure
		Explosion.BlastRadius = Settings.BlastRadius
		Explosion.DestroyJointRadiusPercent = 0
		Explosion.ExplosionType = "NoCraters"
		Explosion.Visible = Settings.ExplosionVisible
	end

	-- Find the character's model
	local Char = hit:FindFirstAncestorWhichIsA("Model")

	-- If the character exists
	if Char and Char:FindFirstChild("Humanoid") then

		-- Create hitmarker
		local HitMark = Hitmarker:Clone()
		HitMark.Parent = BulletFolder
		HitMark.Position = result.Position
		HitMark.Gui.Frame.Texture.Image = Settings.Hitmarker

		-- Create hitmarker sound
		local HitSound = Instance.new("Sound")
		HitSound.Parent = SoundsFolder
		HitSound.SoundId = Settings.Hitmarksound
		HitSound.Volume = Settings.HitmarkerVolume

		-- Play hitmarker sound
		HitSound:Play()

		-- If it was a headshot
		if hit.Name == "Head" then

			-- Damage player
			Char.Humanoid.Health = Char.Humanoid.Health - (Settings.Damage * Settings.HeadShotMult)

			if Char.Humanoid.Health == 0 then

				GiveKill(Settings.Owner, Char)
			end
		else

			-- Damage player
			Char.Humanoid.Health = Char.Humanoid.Health - Settings.Damage

			if Char.Humanoid.Health == 0 then

				GiveKill(Settings.Owner, Char)
			end
		end

		-- Destroy bullet after some time
		game:GetService("Debris"):AddItem(bullet, 0.1)

		-- Destroy hitmarkers after some time
		game:GetService("Debris"):AddItem(HitMark, Settings.HitmarkDuration)
	end
end


-- When the length of the trajectory changes
local function OnLenChange(cast, lastPoint, dir, len, velocity, bullet)

	if bullet then
		
		-- Meth
		local BulletLength = bullet.Size.Z / 2
		local Offset = CFrame.new(0, 0, -(len - BulletLength))
		bullet.CFrame = CFrame.lookAt(lastPoint, lastPoint + dir):ToWorldSpace(Offset)
	end
end


-- When the player fires
local function fire(Player, MousePos)

	-- If the gun is firing or being reloaded or the player is dead
	if Settings.Firing == true or Settings.Reloading == true or Settings.GunDown == true or game.Workspace[Player.Name].Humanoid.Health <= 0 then

		return
	end

	if Settings.CurrentMag > 0 then

		Settings.Firing = true

		-- Set muzzle position and line of fire
		local Origin = GunMuzzle.WorldPosition
		local Dir = (MousePos - Origin).Unit

		-- Fire the bullet
		Caster:Fire(Origin, Dir, Settings.BulletVel, CastBehavior)

		-- Create firing sound
		local GunFire = Instance.new("Sound")
		GunFire.Parent = SoundsFolder
		GunFire.SoundId = Settings.FireSound
		GunFire.Volume = Settings.FireVolume

		-- Play fire sound
		GunFire:Play()

		-- Decrease ammo by 1
		Settings.CurrentMag -= 1

		-- Update ammo counter
		UpdateAmmo(Player)
		
		-- Eject casing
		EjectCasing()
		
		-- Tell client to give recoil
		RecoilEvent:FireClient(Player)

		if Settings.MuzzleFlash == true then

			-- Show muzzle flash
			ShowFlash()
		end

		-- If the gun is an rpg, hide the mag
		if Settings.IsRPG == true then
			
			-- Get mag's parts
			local Kids = Mag:GetDescendants()

			-- Make mag invisible
			for _, obj in pairs(Kids) do

				if obj:IsA("BasePart") or obj:IsA("MeshPart") or obj:IsA("UnionOperation") then

					obj.Transparency = 1
				end
			end
		end

		-- Wait so that we don't destroy the server
		task.wait(FireDelay)

		Settings.Firing = false

	else if Settings.CurrentMag <= 0 then

			-- If there is no ammo, click the gun
			GunClick()

			if Settings.AutoReload == true then

				reload(Player)
			end
		end
	end
end

-- When the player reloads
function reload(Player)

	-- End function if mag is full or the gun is already being reloaded
	if Settings.CurrentMag == Settings.MaxMag or Settings.Reloading == true then

		return
	end

	if Settings.CurrentReserve >= Settings.MaxMag then

		-- Set reloading status to true
		Settings.Reloading = true

		-- Make mag invisible
		HideMag()

		-- Create reloading sound
		local ReloadSound = Instance.new("Sound")
		ReloadSound.Parent = SoundsFolder
		ReloadSound.SoundId = Settings.ReloadSound
		ReloadSound.Volume = Settings.ReloadVolume

		-- Play reload sound
		ReloadSound:Play()

		if Settings.ReloadAnim ~= nil then

			ReloadAnimTrack:Play(0.100000001, 1, 1.25)
		end

		-- Wait for duration of reload
		task.wait(Settings.ReloadDur)
		
		-- Calculate needed ammo
		local AmmoNeeded = Settings.MaxMag - Settings.CurrentMag
		
		-- Add ammo to the mag
		Settings.CurrentMag += AmmoNeeded
		
		-- Remove ammo from the reserve
		Settings.CurrentReserve -= AmmoNeeded

		-- Make mag visible
		ShowMag()
		
		if Gun.Parent.Parent == game.Workspace then
			
			-- Update ammo counter
			UpdateAmmo(Player)
		end
		-- Set reloading stauts to false
		Settings.Reloading = false

	else if Settings.CurrentReserve > 0 and Settings.CurrentReserve < Settings.MaxMag then

			-- Set reloading status to true
			Settings.Reloading = true
			
			-- Make mag invisible
			HideMag()

			-- Create reloading sound
			local ReloadSound = Instance.new("Sound")
			ReloadSound.Parent = SoundsFolder
			ReloadSound.SoundId = Settings.ReloadSound
			ReloadSound.Volume = Settings.ReloadVolume

			-- Play reload sound
			ReloadSound:Play()
			
			-- If reload anim shoud be played
			if Settings.ReloadAnim ~= nil then
				
				ReloadAnimTrack:Play(0.100000001, 1, 1.25)
			end

			-- Wait for duration of reload
			task.wait(Settings.ReloadDur)

			-- Calculate needed ammo
			local AmmoNeeded = Settings.MaxMag - Settings.CurrentMag

			-- If the required ammo is more than the reserve
			if AmmoNeeded > Settings.CurrentReserve then

				AmmoNeeded = Settings.CurrentReserve
			end

			-- Add ammo to the mag
			Settings.CurrentMag = AmmoNeeded

			-- Remove ammo from the reserve
			Settings.CurrentReserve -= AmmoNeeded

			-- Make mag visible
			ShowMag()

			if Gun.Parent.Parent == game.Workspace then

				-- Update ammo counter
				UpdateAmmo(Player)
			end

			-- Set reloading stauts to false
			Settings.Reloading = false
		end
	end
end

-- When player puts the weapon down
function GunDown()
	
	-- Check if the function should be ran
	if Settings.Firing or Settings.Reloading or Settings.GunDownAnim == nil then
		
		return
	end
	
	-- If the gun is currently down
	if Settings.GunDown == true then
		
		-- End down anim and start the idle anim
		DownAnimTrack:Stop()
		IdleAnimTrack:Play()
		
		-- Set down to false
		Settings.GunDown = not Settings.GunDown
		
	-- If the gun is currently up
	elseif Settings.GunDown == false then

		-- Start down anim and end the idle anim
		IdleAnimTrack:Stop()
		DownAnimTrack:Play()

		-- Set down to true
		Settings.GunDown = not Settings.GunDown
	end
end


-- Fire and reload events
FireEvent.OnServerEvent:Connect(fire)
ReloadEvent.OnServerEvent:Connect(reload)

-- GunDown event
DownEvent.OnServerEvent:Connect(GunDown)

-- Equip and upequip events
Gun.Equipped:Connect(onEquipped)
Gun.Unequipped:Connect(onUnequipped)

-- When the length of the trajectory changes
Caster.LengthChanged:Connect(OnLenChange)

-- When raycast hits something
Caster.RayHit:Connect(OnRayHit)


-- Show the gun's muzzle flash when the gun is fired
function ShowFlash()

	-- Create firing sound
	local GunFire = Instance.new("Sound")
	GunFire.Parent = SoundsFolder
	GunFire.SoundId = Settings.FireSound
	GunFire.Volume = Settings.FireVolume

	-- Enable flash and light
	MuzzleLight.Enabled = true
	FlashParticles.Enabled = true

	-- Play fire sound
	GunFire:Play()
end


-- Show the gun's muzzle flash when the gun is fired
function GunClick()

	-- Create clicking sound
	local GunClick = Instance.new("Sound")
	GunClick.Parent = SoundsFolder
	GunClick.SoundId = Settings.ClickSound
	GunClick.Volume = Settings.ClickVolume

	-- Play clicking sound
	GunClick:Play()
end


-- Function to update displayed ammo
function UpdateAmmo(Player)

	-- Get player's ammo GUI
	local CurrentGui = game.Players[Player.Name].PlayerGui["AmmoGui"]

	-- Change gui text
	CurrentGui.Frame.AmmoLabel.Text = Settings.CurrentMag .. " | " .. Settings.CurrentReserve
end


-- Function to give players kills
function GiveKill(Player, HitChar)
	
	if not Settings.GiveKills then
		
		return
	end
	
	-- Check if the hit player is already dead
	local IsKilled = false
	for i, Obj in pairs(HitChar:GetChildren()) do
		
		if Obj.Name == "IsKilled" and Obj.ClassName == "BoolValue" then
			
			-- If the player was already killed, end the function
			IsKilled = true
			return
		end
	end
	
	-- If the player was not killed yet
	if IsKilled == false then
		
		-- Create the killed value for the hit player
		local KilledObj = Instance.new("BoolValue")
		KilledObj.Parent = HitChar
		KilledObj.Value = true
		KilledObj.Name = "IsKilled"
		
		-- Get player's kills
		local Kills = game.Players[Player].leaderstats.Kills
		
		-- Add to the player's kills
		Kills.Value += 1
	end
end


-- Function to eject shell casings
function EjectCasing()
	
	-- Check if casings are enabled
	if not Settings.EjectBullets then
		
		return
	end
	
	-- Clone the case and set its position
	local CaseClone = EmptyCasing:Clone()
	CaseClone.Parent = CasingsFolder
	CaseClone.CFrame = EjectPort.CFrame
	
	-- If cases eject with force
	if Settings.CaseForce == true then
		
		-- Calculate vector3 force
		local ForceDev = math.random(Settings.MinForce, Settings.MaxForce) / 10
		
		-- Apply force
		CaseClone:ApplyImpulse(EjectPort.CFrame.RightVector / ForceDev + Settings.ForceVector)
	end
	
	-- Add the casings to the debris service
	Debris:AddItem(CaseClone, Settings.CaseDur)
end


-- Function to hide the magazine
function HideMag()
	-- Check if mag should be hidden
	if not Settings.HideMag then
		
		return
	end

	-- Get mag's parts
	local Kids = Mag:GetDescendants()
	-- Make mag invisible
	for _, obj in pairs(Kids) do
		-- If the object is a part
		if obj:IsA("BasePart") or obj:IsA("MeshPart") or obj:IsA("UnionOperation") then
			-- Make the object transparent
			obj.Transparency = 1
		end
	end
end

-- Function to show the magazine
function ShowMag()
	-- Get mag's parts
	local Kids = Mag:GetDescendants()
	-- Make mag visible
	for _, obj in pairs(Kids) do
		-- If the object is a part
		if obj:IsA("BasePart") or obj:IsA("MeshPart") or obj:IsA("UnionOperation") then
			-- If the part is a window
			if obj.Name ~= "Window" then
				-- Make object visible
				obj.Transparency = 0
			else
				-- Make the object partially visible
				obj.Transparency = 0.9
			end
		end
	end
end


-- Every 10 seconds, execute a S&D on the sound
while task.wait(10) do
	local Kids = SoundsFolder:GetChildren()
	for _,Obj in pairs(Kids) do
		if Obj.Name == "Sound" then
			if Obj.Playing == false then
				Obj:Destroy()
			end
		end
	end
end
