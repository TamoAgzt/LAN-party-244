import os
import discord
from dotenv import load_dotenv
from discord.ext import commands

load_dotenv()

Intents = discord.Intents.default()
Intents.members = True

Client = commands.Bot(
    command_prefix='-',
    case_insensitive=True,
    intents=Intents,
    help_command=None,
)

@Client.event
async def on_ready():
    Activity = discord.Game(f'{Client.command_prefix}help')
    await Client.change_presence(status=discord.Status.online, activity=Activity)
    print(f'Logged in as {Client.user}')

import json
import asyncio
import discord
import includes
import datetime
from discord.ext import commands
from attributes import adminatt as a

TimeFormat = '%H:%M:%S (gmt-1) on %a %d/%m/%y'
JsonFile = 'json/warns.json'

class admin(commands.Cog, name='General admin commands'):
    def __init__(self, bot):
        self.bot = bot

    @commands.command(aliases=a.getuser['aliases'], brief=a.getuser['brief'], description=a.getuser['description'], enabled=a.getuser['enabled'], hidden=a.getuser['hidden'], usage=a.getuser['usage'])
    @commands.has_permissions(mention_everyone=True)
    async def getuser(self, ctx, userid):
        await ctx.reply(f"<@!{userid}>")

    @commands.command(aliases=a.getperms['aliases'], brief=a.getperms['brief'], description=a.getperms['description'], enabled=a.getperms['enabled'], hidden=a.getperms['hidden'], usage=a.getperms['usage'])
    @commands.has_permissions(manage_roles=True)
    async def getperms(self, ctx, mention=None):
        # Check if a user was mentioned or default to author #
        user = ctx.message.mentions[0] if len(ctx.message.mentions) > 0 else ctx.author

        # Get users perms and create embed #
        UserPerms = dict(user.guild_permissions)
        EmbedText = f',\n'.join(f'{key}: {val}' for key, val in UserPerms.items())
        Embed = discord.Embed(title=f'Permissions for {user.name}', description=EmbedText, color=includes.randomcolor())
        await ctx.reply(embed=Embed)

    @commands.command(aliases=a.kick['aliases'], brief=a.kick['brief'], description=a.kick['description'], enabled=a.kick['enabled'], hidden=a.kick['hidden'], usage=a.kick['usage'])
    @commands.has_permissions(kick_members=True)
    async def kick(self, ctx, mention, kickreason):
        # Check if a user was mentioned #
        user = ctx.message.mentions[0] if len(ctx.message.mentions) > 0 else None
        if user is None:
            await ctx.reply(embed=discord.Embed(title="Request failed!", description="You need to mention a user to kick.", color=includes.randomcolor()))
            return

        # Check whether reason is within allowed length #
        if len(kickreason) > 50:
            await ctx.reply(embed=discord.Embed(title="Request failed!", description="Your reason has to be shorter.", color=includes.randomcolor()))
            return

        # Kick member and report back to the user #
        await ctx.guild.kick(user=user, reason=kickreason)
        await ctx.reply(embed=discord.Embed(title="Success!", description=f"{user.name} was kicked from the server"))

    @commands.command(aliases=a.ban['aliases'], brief=a.ban['brief'], description=a.ban['description'], enabled=a.ban['enabled'], hidden=a.ban['hidden'], usage=a.ban['usage'])
    @commands.has_permissions(ban_members=True)
    async def ban(self, ctx, mention, banreason):
        # Check if a user was mentioned #
        user = ctx.message.mentions[0] if len(ctx.message.mentions) > 0 else None
        if user is None:
            await ctx.reply(embed=discord.Embed(title="Request failed!", description="You need to mention a user to ban.", color=includes.randomcolor()))
            return

        # Check whether reason is within allowed length #
        if len(banreason) > 50:
            await ctx.reply(embed=discord.Embed(title="Request failed!", description="Your reason has to be shorter.", color=includes.randomcolor()))
            return

        # Ban member and report back to the user #
        await ctx.guild.ban(user=user, reason=banreason, delete_message_days=0)
        await ctx.reply(embed=discord.Embed(title="Success!", description=f"{user.name} was banned from the server", color=includes.randomcolor()))

    @commands.command(aliases=a.warn['aliases'], brief=a.warn['brief'], description=a.warn['description'], enabled=a.warn['enabled'], hidden=a.warn['hidden'], usage=a.warn['usage'])
    @commands.has_permissions(kick_members=True)
    async def warn(self, ctx, mention, reason):
        # Check if a user was mentioned #
        user = ctx.message.mentions[0] if len(ctx.message.mentions) > 0 else None
        if user is None:
            await ctx.reply(embed=discord.Embed(title="Request failed!", description="You need to mention a user to warn.", color=includes.randomcolor()))
            return

        # Check whether reason is within allowed length #
        if len(reason) > 50:
            await ctx.reply(embed=discord.Embed(title="Request failed!", description="Your reason has to be shorter.", color=includes.randomcolor()))
            return

        # Read current data from file #
        with open(JsonFile, 'r') as file:
            data = dict(json.load(file))
            file.close()

        # Include time in warn #
        CurrentTime = datetime.datetime.today()
        CurrentTime = CurrentTime.strftime(TimeFormat)
        CurrentTime = f" (at {CurrentTime})."

        # Check whether there is data or not #
        if str(user.id) in data:
            data[str(user.id)]['warns'].append([reason, str(ctx.author.id), CurrentTime])
        else:
            data[user.id] = {"warns": [[reason, str(ctx.author.id), CurrentTime]]}

        # Write updated data to file and clear data #
        with open(JsonFile, 'w') as file:
            json.dump(data, file)
            file.close()
            data.clear()

        # Report back to the user #
        await ctx.reply(embed=discord.Embed(title='Success!', description=f'Warned {user.name} for {reason}', color=includes.randomcolor()))

    @commands.command(aliases=a.showwarns['aliases'], brief=a.showwarns['brief'], description=a.showwarns['description'], enabled=a.showwarns['enabled'], hidden=a.showwarns['hidden'], usage=a.showwarns['usage'])
    @commands.has_permissions(kick_members=True)
    async def showwarns(self, ctx, mention):
        # Check if a user was mentioned #
        user = ctx.message.mentions[0] if len(ctx.message.mentions) > 0 else None
        if user is None:
            await ctx.reply(embed=discord.Embed(title="Request failed!", description="You need to mention a user to show.", color=includes.randomcolor()))
            return

        # Read current data from file #
        with open(JsonFile, 'r') as file:
            data = dict(json.load(file))
            file.close()

        # Prepare discord embed #
        Embed = discord.Embed(title=f'Warns for {user.name}:', color=includes.randomcolor())

        # Bool to determine whether there is data #
        HasData = False
        for userid, val in data.items():
            # Check if this item is the user we're looking for #
            if str(user.id) == userid:
                for item in data[userid]['warns']:
                    # If data has been found #
                    HasData = True

                    # Define list contents #
                    WarnReason = item[0]
                    AdminName = self.bot.get_user(int(item[1])).name
                    AdminID = item[1]
                    WarnTime = item[2]

                    # Prepare text and add field to embed #
                    Text = f'Admin name: \'{AdminName}\'.\nAdmin ID: {AdminID}\nTime: {WarnTime}'
                    Embed.add_field(name=WarnReason, value=Text, inline=False)
help = {
    'aliases': ['h'],
    'brief': 'Sends this message.',
    'description': 'Sends a message with all commands, or with the usage for 1 command if specified.',
    'enabled': True,
    'hidden': False,
    'usage': 'command*'
}

        # Report back to user #
        if HasData:
            await ctx.reply(embed=Embed)
        elif not HasData:
            await ctx.reply(embed=discord.Embed(title='No data!', description='No data was found for this user.', color=includes.randomcolor()))

    @commands.command(aliases=a.muteuser['aliases'], brief=a.muteuser['brief'], description=a.muteuser['description'], enabled=a.muteuser['enabled'], hidden=a.muteuser['hidden'], usage=a.muteuser['usage'])
    @commands.has_permissions(mute_members=True)
    async def muteuser(self, ctx, mention=None, reason=None, hours=None, mins=None, secs=None):
        # Check if a user was mentioned #
        user = ctx.message.mentions[0] if len(ctx.message.mentions) > 0 else None
        if user is None:
            await ctx.reply(embed=discord.Embed(title="Request failed!", description="You need to mention a user to mute.", color=includes.randomcolor()))
            return

        # Check if a reason was given #
        reason = reason if reason is not None else 'No reason given'

        # Find muted role #
        MuteRole = None
        roles = ctx.message.guild.roles
        for role in roles:
            if role.name == 'Muted':
                MuteRole = role
                break

        # Get seconds the mute will take #
        Dur = ConvertTime(hours, mins, secs)

        # Add role to mute the user #
        await user.add_roles(MuteRole, reason=reason)

        # Mute msg embed #
        MuteEmbed = discord.Embed(title=f'Muted {user.name}')
        MuteEmbed.add_field(name='Reason:', value=reason, inline=False)

        if Dur > 0:
            MuteEmbed.add_field(name='Duration:', value=f'Hours: {hours}.\nMins: {mins}.\nSecs: {secs}.\nTotal secs: {Dur}.', inline=False)
        elif Dur == 0:
            MuteEmbed.add_field(name='Duration:', value='Undefined', inline=False)

        # Report back to author #
        await ctx.reply(embed=MuteEmbed)

        if Dur > 0:
            # Wait for mute to end #
            await asyncio.sleep(Dur)

            # Find and delete muted role #
            roles = user.roles
            for role in roles:
                if role.name == 'Muted':
                    await user.remove_roles(role, reason='Mute ended.')

                    try:
                        await ctx.reply(embed=discord.Embed(title=f'Unmuted {user.name}', description=f'Unmuted {user.mention}, for mute ended.'))
                    except Exception:
                        await ctx.send(embed=discord.Embed(title=f'Unmuted {user.name}', description=f'Unmuted {user.mention}, for mute ended.'))


    @commands.command(aliases=a.unmuteuser['aliases'], brief=a.unmuteuser['brief'], description=a.unmuteuser['description'], enabled=a.unmuteuser['enabled'], hidden=a.unmuteuser['hidden'], usage=a.unmuteuser['usage'])
    @commands.has_permissions(mute_members=True)
    async def unmuteuser(self, ctx, mention=None, reason=None):
        # Check if a user was mentioned #
        user = ctx.message.mentions[0] if len(ctx.message.mentions) > 0 else None
        if user is None:
            await ctx.reply(embed=discord.Embed(title="Request failed!", description="You need to mention a user to unmute.", color=includes.randomcolor()))
            return

        # Check if a reason was given #
        reason = reason if reason is not None else 'No reason given'

        # Find and delete muted role #
        roles = user.roles
        for role in roles:
            if role.name == 'Muted':
                await user.remove_roles(role, reason=reason)
                await ctx.reply(embed=discord.Embed(title=f'Unmuted {user.name}', description=f'Unmuted {user.mention}, for {reason}'))
                return

        await ctx.reply(embed=discord.Embed(title=f'Request failed!', description=f'It seems {user.mention} is not muted.'))


def setup(bot):
    bot.add_cog(admin(bot))


def ConvertTime(hours, mins, secs):
    # Var to store total time #
    TotalSecs = 0

    # Check if input was given #
    if hours not in [None, '0', '-']:
        TotalSecs +=  int(hours) * 3600
    if mins not in [None, '0', '-']:
        TotalSecs += int(mins) * 60
    if secs not in [None, '0', '-']:
        TotalSecs += int(secs)

    return TotalSecsimport discord
import includes
from discord.ext import commands
from attributes import channeladmin as a

LockedChannels = []
lockchannel = {
    'aliases': ['l', 'lock'],
    'brief': 'Keep users from sending messages.',
    'description': 'Stops non-admin users from sending messages in the specified channel.',
    'enabled': True,
    'hidden': False,
    'usage': '#channel*'
}

unlock = {
    'aliases': ['u', 'unl'],
    'brief': 'Unlocks channel.',
    'description': 'Lets non-admin users send messages in a previously locked server.',
    'enabled': True,
    'hidden': False,
    'usage': '#channel*'
}

purge = {
    'aliases': ['p', 'purgechannel'],
    'brief': 'Purges a channel.',
    'description': 'Purges a channel of all messages unless an amount or user is specified.',
    'enabled': True,
    'hidden': False,
    'usage': 'amount(number or inf)* @user* #channel*'
}

spam = {
    'aliases': ['s', 'sendspam'],
    'brief': 'Spams a message.',
    'description': 'Spams the entered message a certain amount.',
    'enabled': True,
    'hidden': False,
    'usage': 'amount "msg" channel*'
}

class channeladmin(commands.Cog, name='Channel admin commands'):
    def __init__(self, bot):
        self.bot = bot

    @commands.command(aliases=a.lockchannel['aliases'], brief=a.lockchannel['brief'], description=a.lockchannel['description'], enabled=a.lockchannel['enabled'], hidden=a.lockchannel['hidden'], usage=a.lockchannel['usage'])
    @commands.has_permissions(manage_channels=True)
    async def lockchannel(self, ctx, channel=None):
        # Check if a channel was mentioned #
        channel = ctx.message.channel_mentions[0] if len(ctx.message.channel_mentions) > 0 else ctx.channel

        # Add locked channel to list #
        LockedChannels.append(channel)

        # Edit channel perms and report to user #
        await channel.set_permissions(ctx.guild.default_role, send_messages=False)
        await ctx.reply(embed=discord.Embed(title='Success!', description=f'Locked {channel.name}.', color=includes.randomcolor()))

    @commands.command(aliases=a.unlock['aliases'], brief=a.unlock['brief'], description=a.unlock['description'], enabled=a.unlock['enabled'], hidden=a.unlock['hidden'], usage=a.unlock['usage'])
    @commands.has_permissions(manage_channels=True)
    async def unlock(self, ctx, channel=None):
        # Check if a channel was mentioned #
        channel = ctx.message.channel_mentions[0] if len(ctx.message.channel_mentions) > 0 else ctx.channel

        # Check if given channel is locked #
        if channel not in LockedChannels:
            await ctx.reply(embed=discord.Embed(title='Request failed!', description='This channel is not locked', color=includes.randomcolor()))
            return

        # Edit channel perms and report to user #
        await channel.set_permissions(ctx.guild.default_role, send_messages=True)
        await ctx.reply(embed=discord.Embed(title='Success!', description=f'Unlocked {channel.name}.', color=includes.randomcolor()))

    @commands.command(aliases=a.purge['aliases'], brief=a.purge['brief'], description=a.purge['description'], enabled=a.purge['enabled'], hidden=a.purge['hidden'], usage=a.purge['usage'])
    @commands.has_permissions(manage_channels=True)
    async def purge(self, ctx, amount=None, user=None, channel=None):
        # Get channel and user if available #
        channel = ctx.message.channel_mentions[0] if len(ctx.message.channel_mentions) > 0 else ctx.channel
        user = ctx.message.mentions[0] if len(ctx.message.mentions) > 0 else None

        # Default amount to none if user entered inf #
        amount = None if amount == 'inf' else amount

        # If no user specified, delete any messages #
        purged = []
        if user is None:
            purged = await channel.purge(limit=amount)

        # If a user if specified, loop through message history #
        elif user is not None:
            # Get channel history #
            history = await channel.history(limit=amount).flatten()

            # Loop through messages #
            for msg in history:
                # Check if this message should be deleted #
                if msg.author.id is user.id:
                    await msg.delete()
                    purged.append(msg)

        try:
            await ctx.reply(embed=discord.Embed(title=f'Purged {channel.name}', description=f'Purged {len(purged)} messages.', color=includes.randomcolor()))
        except:
            await ctx.send(embed=discord.Embed(title=f'Purged {channel.name}', description=f'Purged {len(purged)} messages.', color=includes.randomcolor()))

    @commands.command(aliases=a.spam['aliases'], brief=a.spam['brief'], description=a.spam['description'], enabled=a.spam['enabled'], hidden=a.spam['hidden'], usage=a.spam['usage'])
    @commands.has_permissions(manage_channels=True)
    async def spam(self, ctx, amount, msg, channel=None):
        # Get channel if one is mentioned #
        channel = ctx.message.channel_mentions[0] if len(ctx.message.channel_mentions) > 0 else ctx.channel

        # Send specified amount of messages #
        for num in range(1, int(amount)):
            await channel.send(msg)

        # Report back to the user #
        await ctx.reply(embed=discord.Embed(title=f'Spammed {channel.name}', description=f'Sent {msg} {amount} times in {channel.name}.', color=includes.randomcolor()))


def setup(bot):
    bot.add_cog(channeladmin(bot))
import discord
import includes
from discord.ext import commands


class errorhandler(commands.Cog, name='errorhandler'):
    def __init__(self, bot):
        self.bot = bot

    @commands.Cog.listener()
    async def on_command_error(self, ctx, error: commands.CommandError):

        message = None
        if isinstance(error, commands.MissingRequiredArgument):
            message = f'You missed a required argument "{error.param}"'
        elif isinstance(error, commands.MissingPermissions):
            message = f'You\'re missing permissions to do that.'
        elif isinstance(error, commands.BotMissingPermissions):
            message = f'I don\'t have permissions to run this command.'
        elif isinstance(error, commands.CommandNotFound):
            message = f'This command does not exist.'
        else:
            message = f'Error: \'{error}\'.\nIf this is a reoccurring issue, please ask for assistance.'

        print(f'Error {error} in message \'{ctx.message.content}\' by {ctx.author}')
        await ctx.reply(embed=discord.Embed(title='An error occurred!', description=message, color=includes.randomcolor()))
import discord
from discord.ext import commands
from attributes import helpatt as a


class HelpComm(commands.Cog, name='Help commands'):
    def __init__(self, bot):
        self.bot = bot
getuser = {
    'aliases': ['mention', 'user'],
    'brief': 'Mentions a user',
    'description': 'Mentions the user who\'s ID was entered',
    'enabled': True,
    'hidden': False,
    'usage': '"User ID"'
}

getperms = {
    'aliases': ['g', 'perms'],
    'brief': 'Gets a user\'s perms.',
    'description': 'Gets all of a specified user\'s perms, requires manage roles.',
    'enabled': True,
    'hidden': False,
    'usage': '@user*',
}

kick = {
    'aliases': ['k', 'kickmember'],
    'brief': 'Kicks user from the server,',
    'description': 'Kicks the specified user from the server.',
    'enabled': True,
    'hidden': False,
    'usage': '"reason" @user'
}

ban = {
    'aliases': ['b', 'banmember'],
    'brief': 'Bans user from the server',
    'description': 'Bans the specified user from the server for undetermined time',
    'enabled': True,
    'hidden': False,
    'usage': '"reason" @user'
}

warn = {
    'aliases': ['w', 'warnuser'],
    'brief': 'Formally warns a user',
    'description': 'Formally warns specified user for the given reason.',
    'enabled': True,
    'hidden': False,
    'usage': '@user "reason"'
}

showwarns = {
    'aliases': ['warns', 'show'],
    'brief': 'Shows warns for a user',
    'description': 'Shows all warns for the specified user',
    'enabled': True,
    'hidden': False,
    'usage': '@user'
}

muteuser = {
    'aliases': ['mute', 'm'],
    'brief': 'Prevents a user from messaging.',
    'description': 'Prevents a user from sending messages for a specified duration',
    'enabled': True,
    'hidden': False,
    'usage': '@user reason* hours* minutes* seconds*'
}

unmuteuser = {
    'aliases': ['unmute', 'um'],
    'brief': 'Unmutes a user',
    'description': 'Unmutes specified user for the given reason',
    'enabled': True,
    'hidden': False,
    'usage': '@user reason*'
}

    @commands.command(aliases=a.help['aliases'], brief=a.help['brief'], description=a.help['description'], enabled=a.help['enabled'], hidden=a.help['hidden'], usage=a.help['usage'])
    async def help(self, ctx, comm=None):
        if comm is not None:
            for command in self.bot.commands:
                if command.name == comm or comm in command.aliases:
                    # Define help text #
                    HelpText = f'``` Help for command {command.name}({comm}): \n'
                    HelpText += f'(Params with a * are optional, note that you cannot skip params though.)\n\n'
                    HelpText += f'{command.description}\n'
                    HelpText += f'Aliases: {[al for al in command.aliases]}\n'
                    HelpText += f'Usage: {self.bot.command_prefix}{command.name} {command.usage}'
                    HelpText += '```'

                    # Send help text and end function #
                    await ctx.reply(HelpText)
                    return
            await ctx.reply(embed=discord.Embed(title='Request failed!', description=f'Could not find command \'{comm}\'.'))
        else:
            # Define start of help text #
            HelpText = '``` Help for all commands: \n'

            # Loop through cogs #
            Cogs = self.bot.cogs
            for cog in Cogs.values():
                # Start counting commands and backup text #
                ShownAmount = 0
                TextBackup = HelpText

                # Add cog info to command #
                HelpText += f'\n  --  {cog.qualified_name}:  --  \n'
                for command in cog.get_commands():
                    # Check if command should be shown #
                    if command.brief is None or command.hidden is True: continue

                    # Add command to text #
                    ShownAmount += 1
                    HelpText += f'{command.name}:   {command.brief}\n'

                # Check whether enough commands are shown #
                if ShownAmount < 1:
                    HelpText = TextBackup
                    continue

            # End help text and send text #
            HelpText += '```'
            await ctx.repimport includes
from discord.ext import commands

class testing(commands.Cog, name='Test commands'):
    def __init__(self, bot):
        self.bot = bot

    @commands.command(hidden=True)
    async def test(self, ctx):
        await includes.log(ctx)
    
    @commands.command(hidden=True)
    async def testget(self, ctx, arg):
        # Check if a user was mentioned #
        user = ctx.message.mentions[0] if len(ctx.message.mentions) > 0 else None
        
def setup(bot):
    bot.add_cog(testing(bot))
ly(HelpText)


def setup(bot):
    bot.add_cog(HelpComm(bot))

def setup(bot):
    bot.add_cog(errorhandler(bot))


if __name__ == "__main__":
    Client.run(os.getenv('TOKEN'))
    import json
import discord
from random import choice
from discord import Color as c

JsonFile = 'json/logs.json'

colors = [
	c.from_rgb(255, 255, 255),		# White
	c.from_rgb(100, 100, 100),		# Grey
	c.from_rgb(0, 0, 0),			# Black
	c.from_rgb(255, 100, 100),		# Light red
	c.from_rgb(255, 0, 0),			# Red
	c.from_rgb(155, 0, 0),			# Dark red
	c.from_rgb(255, 100, 0),		# Orange
	c.from_rgb(255, 255, 0),		# Yellow
	c.from_rgb(125, 150, 100),		# Light green
	c.from_rgb(0, 255, 0),			# Green
	c.from_rgb(40, 70, 45),			# Dark green
	c.from_rgb(0, 175, 255),		# Light blue
	c.from_rgb(0, 0, 255),			# Blue
	c.from_rgb(0, 35, 100),			# Dark blue
	c.from_rgb(255, 150, 220),		# Light pink
	c.from_rgb(255, 0, 255),		# Pink
	c.from_rgb(170, 0, 170),		# Magenta
	c.from_rgb(125, 0, 125)			# Purple
]

# Function to return a random embed color #
def randomcolor():
	# Return a random color #
	return choice(colors)

async def log(ctx, args=[]):
	# Check if a user was mentioned #
	user = ctx.message.mentions[0] if len(ctx.message.mentions) > 0 else None
	
	MsgId = ctx.message.id
	Admin = ctx.author
	CmdName = ctx.command.name
	Target = user if user is not None else 'None'
	
	LogData = {
		'Admin': f'{Admin.name}#{Admin.discriminator}',
		'AdminId': Admin.id,
		'CommandName': CmdName,
		'Target': f'{Target.name}#{Target.discriminator}' if user is not None else None,
		'TargetId': Target.id if user else None,
		'Args': args,
	}
	
	with open(JsonFile, 'r') as file:
		JsonCont = dict(json.load(file))
	
	JsonCont[MsgId] = LogData
	
	with open(JsonFile, 'w') as file:
		json.dump(JsonCont, file)
	
	await ctx.reply(f'Admin: {Admin} : {Admin.id}\nCmd: {CmdName}\nTarget: {Target} : {Target.id if user else "None"}')

