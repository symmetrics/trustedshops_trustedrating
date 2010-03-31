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
	'Eric Reiche': 'er@symmetrics.de'
}

# =============================================================================
# additional infos
# =============================================================================
INFO = 'Trusted Shops Rating System'

SUMMARY = '''
	Implementiert das Trusted Shops Rating System
'''

NOTES = ''''''

# =============================================================================
# relations
# =============================================================================
REQUIRES = [
	{'magento': '*', 'magento_enterprise': '*'}
]

EXCLUDES = {}

VIRTUAL = {}

DEPENDS_ON_FILES = ()

PEAR_KEY = ''

COMPATIBLE_WITH = {
     'magento': ['1.3.2.3'],
     'magento_enterprise': ['1.3.2.4'],
}
