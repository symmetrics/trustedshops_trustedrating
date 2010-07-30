# encoding: utf-8


# =============================================================================
# package info
# =============================================================================
NAME = 'symmetrics_module_trustedrating'

TAGS = ('symmetrics', 'magento', 'module', 'trustedshops', 'trustedrating',
        'php', 'mrg')

LICENSE = 'AFL 3.0'

HOMEPAGE = 'http://www.symmetrics.de'

INSTALL_PATH = ''


# =============================================================================
# responsibilities
# =============================================================================
TEAM_LEADER = {
    'Torsten Walluhn': 'tw@symmetrics.de',
}

MAINTAINER = {
    'Siegfried Schmitz': 'ss@symmetrics.de',
}

AUTHORS = {
    'Siegfried Schmitz': 'ss@symmetrics.de',
    'Eric Reiche': 'er@symmetrics.de',
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
    {'magento': '*', 'magento_enterprise': '*'},
]

EXCLUDES = {}

VIRTUAL = {}

DEPENDS_ON_FILES = ()

PEAR_KEY = 'magento-community/symmetrics_trustedrating'

COMPATIBLE_WITH = {
     'magento': ['1.4.0.0', '1.4.0.1'],
     'magento_enterprise': ['1.7.0.0'],
}
