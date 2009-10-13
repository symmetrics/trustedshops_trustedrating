# encoding: utf-8


# =============================================================================
# package info
# =============================================================================
NAME = 'symmetrics_module_trustedrating'

TAGS = ('symmetrics', 'magento', 'module', 'trustedshops', 'trustedrating')

LICENSE = 'AFL 3.0'

HOMEPAGE = 'http://www.symmetrics.de'

INSTALL_PATH = ''


# =============================================================================
# responsibilities
# =============================================================================
TEAM_LEADER = {
    'Sergej Braznikov': 'sb@symmetrics.de'
}

MAINTAINER = {
    'Siegfried Schmitz': 'ss@symmetrics.de'
}

AUTHORS = {
    'Siegfried Schmitz': 'ss@symmetrics.de',
}

# =============================================================================
# additional infos
# =============================================================================
INFO = 'Rating System for Trusted Shops'

SUMMARY = '''
	implements the rating system from Trusted Shops
'''

NOTES = '''
    [special notes, restrictions, bugs etc.]
'''

# =============================================================================
# relations
# =============================================================================
REQUIRES = {
}

EXCLUDES = {
}

VIRTUAL = {
}

DEPENDS_ON_FILES = (
    'app/code/core/.../1.php',
    'app/code/core/.../2.php',
    'app/code/core/.../3.php',
)

PEAR_KEY = ''

COMPATIBLE_WITH = {
     'magento': ['1.3.2.3'],
}
