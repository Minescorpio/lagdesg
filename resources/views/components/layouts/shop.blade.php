<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>La Grotte des Geeks</title>

    <!-- Fonts -->
    <link rel="preconnect" href="https://fonts.bunny.net">
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />

    <!-- Scripts -->
    @vite(['resources/css/app.css', 'resources/js/app.js'])

    <!-- Styles -->
    @livewireStyles
    <style>
        [x-cloak] { 
            display: none !important; 
        }
        #particles-js {
            position: fixed;
            width: 100%;
            height: 100%;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            z-index: 0;
        }
        .content-wrapper {
            position: relative;
            min-height: 100vh;
            z-index: 10;
            display: flex;
            flex-direction: column;
        }
        main {
            flex: 1 0 auto;
        }
        footer {
            flex-shrink: 0;
        }
    </style>
</head>
<body class="font-sans antialiased bg-[#1B1D29]" x-data>
    <!-- Particles.js Container -->
    <div id="particles-js"></div>

    <!-- Content Container -->
    <div class="content-wrapper">
        <!-- Header -->
        <header class="bg-[#2E324A]/80 shadow backdrop-blur-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex flex-col items-center justify-center py-6">
                    <!-- Logo -->
                    <div class="flex-shrink-0 mb-8">
                        <a href="{{ route('shop.index') }}">
                            <img src="{{ asset('images/logo.png') }}" alt="{{ config('app.name') }}" class="h-96">
                        </a>
                    </div>

                    <!-- Cart -->
                    <div class="flex items-center mt-4">
                        <a href="{{ route('shop.cart') }}" class="flex items-center text-gray-300 hover:text-white">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                            <span class="ml-2 text-sm font-medium cart-count">
                                {{ count(session('cart', [])) }}
                            </span>
                        </a>
                    </div>
                </div>
            </div>
        </header>

        <!-- Categories Navigation -->
        <nav class="bg-[#2E324A]/80 border-t border-gray-700 backdrop-blur-sm">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex items-center justify-center h-16">
                    <div class="flex space-x-8">
                        @foreach($categories ?? [] as $category)
                            <a href="{{ route('shop.category', $category) }}" 
                               class="text-gray-300 hover:text-white px-3 py-2 rounded-md text-sm font-medium
                                      {{ request()->routeIs('shop.category') && request()->category->id === $category->id ? 'bg-[#1B1D29] text-white' : '' }}">
                                {{ $category->name }}
                            </a>
                        @endforeach
                    </div>
                </div>
            </div>
        </nav>

        <!-- Page Content -->
        <main class="py-12">
            <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
                {{ $slot }}
            </div>
        </main>

        <!-- Footer -->
        <footer class="bg-[#2E324A]/80 border-t border-gray-700 backdrop-blur-sm mt-auto">
            <div class="max-w-7xl mx-auto py-8 px-4 sm:px-6 lg:px-8">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                    <!-- Informations légales -->
                    <div class="text-gray-400">
                        <h3 class="text-lg font-semibold text-white mb-4">{{ __('Informations légales') }}</h3>
                        <ul class="space-y-2">
                            <li>
                                <button type="button" @click="$dispatch('open-modal', 'legal-mentions')" class="text-left w-full hover:text-white">
                                    {{ __('Mentions légales') }}
                                </button>
                            </li>
                            <li>
                                <button type="button" @click="$dispatch('open-modal', 'cgv')" class="text-left w-full hover:text-white">
                                    {{ __('CGV') }}
                                </button>
                            </li>
                            <li>
                                <button type="button" @click="$dispatch('open-modal', 'data-protection')" class="text-left w-full hover:text-white">
                                    {{ __('Protection des données') }}
                                </button>
                            </li>
                            <li>
                                <button type="button" @click="$dispatch('open-modal', 'cookies-policy')" class="text-left w-full hover:text-white">
                                    {{ __('Politique des cookies') }}
                                </button>
                            </li>
                        </ul>
                    </div>

                    <!-- Contact et Service Client -->
                    <div class="text-gray-400">
                        <h3 class="text-lg font-semibold text-white mb-4">{{ __('Service Client') }}</h3>
                        <ul class="space-y-2">
                            <li><a href="#" class="hover:text-white">{{ __('Contact') }}</a></li>
                            <li><a href="#" class="hover:text-white">{{ __('Livraison') }}</a></li>
                            <li><a href="#" class="hover:text-white">{{ __('Retours') }}</a></li>
                            <li><a href="#" class="hover:text-white">{{ __('FAQ') }}</a></li>
                        </ul>
                    </div>

                    <!-- Informations société -->
                    <div class="text-gray-400">
                        <h3 class="text-lg font-semibold text-white mb-4">{{ __('La Grotte des Geeks') }}</h3>
                        <p class="mb-2">SIRET: 851 864 918 00012</p>
                        <p class="mb-2">TVA: FR77851864918</p>
                        <p class="mb-2">Adresse: 2 RUE DES ANGLAIS, 14250 TILLY SUR SEULLES</p>
                        <p class="mb-2">RCS: Caen A 851 864 918</p>
                        <p class="mb-2">Forme juridique: Affaire personnelle commerçant</p>
                        <p class="mb-2">Code APE: 4791A - Vente à distance sur catalogue général</p>
                        <p>Email: contact@lagrottedesgeeks.fr</p>
                    </div>
                </div>

                <div class="mt-8 pt-8 border-t border-gray-700">
                    <div class="text-center text-gray-400">
                        <p>&copy; {{ date('Y') }} La Grotte des Geeks. {{ __('Tous droits réservés.') }}</p>
                        <p class="mt-2 text-sm">
                            {{ __('Conformément à la loi Informatique et Libertés du 6 janvier 1978, vous disposez d\'un droit d\'accès, de rectification et de suppression des données vous concernant.') }}
                        </p>
                    </div>
                </div>
            </div>
        </footer>
    </div>

    <!-- Modal Mentions Légales -->
    <div x-data="{ open: false }"
         @open-modal.window="if ($event.detail === 'legal-mentions') open = true"
         @keydown.escape.window="open = false"
         x-show="open"
         class="fixed inset-0 z-50 overflow-y-auto"
         x-cloak>
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="open = false"></div>

        <!-- Modal panel -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-[#2E324A] rounded-lg max-w-3xl w-full p-6 overflow-y-auto max-h-[90vh]"
                 @click.away="open = false">
                <!-- Modal header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-white">{{ __('Mentions Légales') }}</h2>
                    <button @click="open = false" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal content -->
                <div class="text-gray-300 space-y-4">
                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('Éditeur du site') }}</h3>
                        <p>La Grotte des Geeks</p>
                        <p>2 RUE DES ANGLAIS, 14250 TILLY SUR SEULLES</p>
                        <p>SIRET: 851 864 918 00012</p>
                        <p>Email: contact@lagrottedesgeeks.fr</p>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('Hébergement') }}</h3>
                        <p>Le site est hébergé par [Nom de l'hébergeur]</p>
                        <p>[Adresse de l'hébergeur]</p>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('Propriété intellectuelle') }}</h3>
                        <p>L'ensemble du contenu de ce site (textes, images, vidéos, etc.) est protégé par le droit d'auteur. Toute reproduction ou représentation, intégrale ou partielle, par quelque procédé que ce soit, est strictement interdite sans autorisation préalable.</p>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('Données personnelles') }}</h3>
                        <p>Conformément à la loi Informatique et Libertés du 6 janvier 1978, vous disposez d'un droit d'accès, de rectification et de suppression des données vous concernant. Pour exercer ce droit, veuillez nous contacter par email.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal CGV -->
    <div x-data="{ open: false }"
         @open-modal.window="if ($event.detail === 'cgv') open = true"
         @keydown.escape.window="open = false"
         x-show="open"
         class="fixed inset-0 z-50 overflow-y-auto"
         x-cloak>
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="open = false"></div>

        <!-- Modal panel -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-[#2E324A] rounded-lg max-w-4xl w-full p-6 overflow-y-auto max-h-[90vh]"
                 @click.away="open = false">
                <!-- Modal header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-white">{{ __('Conditions Générales de Vente') }}</h2>
                    <button @click="open = false" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal content -->
                <div class="text-gray-300 space-y-4">
                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('1. Préambule') }}</h3>
                        <p>Les présentes conditions générales de vente s'appliquent entre La Grotte des Geeks, SIRET 851 864 918 00012, et toute personne effectuant un achat sur le site lagrottedesgeeks.fr. Elles constituent le socle unique de la négociation commerciale.</p>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('2. Prix et Produits') }}</h3>
                        <p>Les prix sont indiqués en euros TTC (toutes taxes comprises) hors participation aux frais de traitement et d'expédition. La Grotte des Geeks se réserve le droit de modifier ses prix à tout moment mais les produits seront facturés sur la base des tarifs en vigueur au moment de la validation de la commande.</p>
                        <p>Les produits demeurent la propriété de La Grotte des Geeks jusqu'au complet paiement du prix.</p>
                        <p class="mt-2">Nos produits sont proposés dans deux catégories distinctes :</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>Produits neufs : garantis neufs et sous emballage d'origine</li>
                            <li>Produits d'occasion : conformément à l'article L321-1 et suivants du Code de commerce, ces produits sont d'occasion et ont fait l'objet d'une vérification complète de leur état de fonctionnement</li>
                        </ul>
                        <p class="mt-2">Pour les produits d'occasion :</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>L'état du produit est clairement indiqué dans la description (comme neuf, très bon état, bon état, état correct)</li>
                            <li>Les éventuels défauts cosmétiques ou fonctionnels sont détaillés dans la description</li>
                            <li>Une garantie légale de conformité de 12 mois s'applique (au lieu de 24 mois pour les produits neufs)</li>
                            <li>Le prix tient compte de l'état du produit et de son ancienneté</li>
                            <li>Conformément à l'article 321-7 du Code pénal, toutes les transactions sont consignées dans un registre spécial</li>
                        </ul>
                        <p class="mt-2">La Grotte des Geeks se réserve le droit de :</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>Refuser la vente de tout produit ne répondant pas à nos critères de qualité</li>
                            <li>Demander des justificatifs supplémentaires sur la provenance des produits</li>
                            <li>Refuser toute transaction suspecte conformément à nos obligations légales</li>
                        </ul>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('3. Commande et Compte Client') }}</h3>
                        <p>Pour passer commande, le client doit être âgé d'au moins 18 ans et avoir la capacité juridique. La création d'un compte client est nécessaire pour toute commande.</p>
                        <p>La commande n'est définitive qu'après confirmation du paiement et envoi d'un email de confirmation par La Grotte des Geeks.</p>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('4. Modalités de Paiement') }}</h3>
                        <p>Le paiement s'effectue en ligne par carte bancaire (Visa, MasterCard, autres cartes bleues) via une connexion sécurisée SSL. Les données bancaires sont cryptées et ne transitent jamais en clair sur le réseau.</p>
                        <p>D'autres moyens de paiement peuvent être proposés : PayPal, virement bancaire.</p>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('5. Livraison et Frais de Port') }}</h3>
                        <p>Les produits sont livrés à l'adresse de livraison indiquée lors de la commande. Les délais de livraison sont donnés à titre indicatif :</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>France métropolitaine : 2-3 jours ouvrés</li>
                            <li>DOM-TOM : 5-7 jours ouvrés</li>
                            <li>Europe : 3-5 jours ouvrés</li>
                        </ul>
                        <p>Les frais de port sont calculés en fonction du poids du colis et de la zone de livraison.</p>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('6. Droit de Rétractation') }}</h3>
                        <p>Conformément aux articles L221-18 et suivants du Code de la consommation, le client dispose d'un délai de 14 jours à compter de la réception des produits pour exercer son droit de rétractation, sans avoir à justifier de motifs ni à payer de pénalités.</p>
                        <p>Les frais de retour sont à la charge du client. Les produits doivent être retournés dans leur état d'origine et complets.</p>
                        <p>Le remboursement sera effectué dans un délai de 14 jours à compter de la réception des produits retournés.</p>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('7. Garanties Légales') }}</h3>
                        <p>Tous les produits fournis par La Grotte des Geeks bénéficient de :</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>La garantie légale de conformité (Articles L217-4 et suivants du Code de la consommation)</li>
                            <li>La garantie des vices cachés (Articles 1641 et suivants du Code civil)</li>
                        </ul>
                        <p>En cas de non-conformité d'un produit vendu, il pourra être retourné à La Grotte des Geeks qui le reprendra, l'échangera ou le remboursera.</p>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('8. Protection des Données') }}</h3>
                        <p>Les données personnelles collectées sont nécessaires au traitement de la commande et sont destinées à un usage interne. Conformément au RGPD et à la loi Informatique et Libertés, le client dispose d'un droit d'accès, de rectification et de suppression des données le concernant.</p>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('9. Service Après-Vente') }}</h3>
                        <p>Pour toute question, réclamation ou suivi de commande, notre service client est disponible :</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>Par email : contact@lagrottedesgeeks.fr</li>
                            <li>Via le formulaire de contact sur le site</li>
                        </ul>
                        <p>Nous nous engageons à répondre dans un délai de 48 heures ouvrées.</p>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('10. Litiges') }}</h3>
                        <p>Les présentes CGV sont soumises au droit français. En cas de litige, une solution amiable sera recherchée avant toute action judiciaire. À défaut, les tribunaux français seront seuls compétents.</p>
                        <p>Conformément à l'article L612-1 du Code de la consommation, vous pouvez recourir gratuitement au service de médiation MEDICYS.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Protection des Données -->
    <div x-data="{ open: false }"
         @open-modal.window="if ($event.detail === 'data-protection') open = true"
         @keydown.escape.window="open = false"
         x-show="open"
         class="fixed inset-0 z-50 overflow-y-auto"
         x-cloak>
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="open = false"></div>

        <!-- Modal panel -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-[#2E324A] rounded-lg max-w-4xl w-full p-6 overflow-y-auto max-h-[90vh]"
                 @click.away="open = false">
                <!-- Modal header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-white">{{ __('Protection des Données') }}</h2>
                    <button @click="open = false" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal content -->
                <div class="text-gray-300 space-y-4">
                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('1. Collecte des Données') }}</h3>
                        <p>La Grotte des Geeks collecte les données suivantes :</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>Données d'identification (nom, prénom)</li>
                            <li>Coordonnées (adresse email, numéro de téléphone, adresse postale)</li>
                            <li>Données de paiement (cryptées et sécurisées)</li>
                            <li>Historique des commandes</li>
                            <li>Données de navigation sur le site</li>
                        </ul>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('2. Utilisation des Données') }}</h3>
                        <p>Vos données sont utilisées pour :</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>Traiter vos commandes et assurer leur livraison</li>
                            <li>Gérer votre compte client</li>
                            <li>Vous informer sur l'état de vos commandes</li>
                            <li>Améliorer nos services et votre expérience sur le site</li>
                            <li>Respecter nos obligations légales et réglementaires</li>
                        </ul>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('3. Protection et Sécurité') }}</h3>
                        <p>Nous mettons en œuvre les mesures techniques et organisationnelles appropriées pour protéger vos données :</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>Chiffrement des données sensibles</li>
                            <li>Protocole SSL pour les transactions</li>
                            <li>Accès restreint aux données personnelles</li>
                            <li>Hébergement sécurisé des données en France</li>
                        </ul>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('4. Vos Droits') }}</h3>
                        <p>Conformément au RGPD et à la loi Informatique et Libertés, vous disposez des droits suivants :</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>Droit d'accès à vos données</li>
                            <li>Droit de rectification des données inexactes</li>
                            <li>Droit à l'effacement de vos données</li>
                            <li>Droit à la limitation du traitement</li>
                            <li>Droit à la portabilité de vos données</li>
                            <li>Droit d'opposition au traitement</li>
                        </ul>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('5. Conservation des Données') }}</h3>
                        <p>Vos données sont conservées :</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>Données de compte : jusqu'à la suppression de votre compte</li>
                            <li>Données de commande : 10 ans (obligation légale)</li>
                            <li>Données de paiement : supprimées après la transaction</li>
                            <li>Cookies : 13 mois maximum</li>
                        </ul>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('6. Contact') }}</h3>
                        <p>Pour exercer vos droits ou pour toute question concernant la protection de vos données, contactez-nous :</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>Par email : contact@lagrottedesgeeks.fr</li>
                            <li>Par courrier : 2 RUE DES ANGLAIS, 14250 TILLY SUR SEULLES</li>
                        </ul>
                        <p class="mt-2">Vous pouvez également contacter la CNIL (www.cnil.fr) si vous estimez que vos droits ne sont pas respectés.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Modal Politique des Cookies -->
    <div x-data="{ open: false }"
         @open-modal.window="if ($event.detail === 'cookies-policy') open = true"
         @keydown.escape.window="open = false"
         x-show="open"
         class="fixed inset-0 z-50 overflow-y-auto"
         x-cloak>
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-black bg-opacity-50 transition-opacity" @click="open = false"></div>

        <!-- Modal panel -->
        <div class="relative min-h-screen flex items-center justify-center p-4">
            <div class="relative bg-[#2E324A] rounded-lg max-w-4xl w-full p-6 overflow-y-auto max-h-[90vh]"
                 @click.away="open = false">
                <!-- Modal header -->
                <div class="flex items-center justify-between mb-6">
                    <h2 class="text-xl font-semibold text-white">{{ __('Politique des Cookies') }}</h2>
                    <button @click="open = false" class="text-gray-400 hover:text-white">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                <!-- Modal content -->
                <div class="text-gray-300 space-y-4">
                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('1. Qu\'est-ce qu\'un Cookie ?') }}</h3>
                        <p>Un cookie est un petit fichier texte déposé sur votre terminal (ordinateur, tablette, mobile) lors de votre visite sur notre site. Les cookies nous permettent de reconnaître votre navigateur et de stocker certaines informations pour améliorer votre expérience de navigation.</p>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('2. Types de Cookies Utilisés') }}</h3>
                        <p>Notre site utilise différents types de cookies :</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>Cookies essentiels : nécessaires au fonctionnement du site (session, panier)</li>
                            <li>Cookies de performance : analyse du trafic et de l'utilisation du site</li>
                            <li>Cookies fonctionnels : mémorisation de vos préférences</li>
                            <li>Cookies de personnalisation : amélioration de votre expérience utilisateur</li>
                        </ul>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('3. Durée de Conservation') }}</h3>
                        <p>Les cookies ont une durée de vie variable :</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>Cookies de session : supprimés à la fermeture du navigateur</li>
                            <li>Cookies persistants : maximum 13 mois conformément aux recommandations de la CNIL</li>
                        </ul>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('4. Gestion des Cookies') }}</h3>
                        <p>Vous pouvez à tout moment :</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>Accepter ou refuser les cookies via notre bandeau de consentement</li>
                            <li>Modifier vos préférences dans les paramètres de votre navigateur</li>
                            <li>Supprimer les cookies déjà installés sur votre terminal</li>
                        </ul>
                        <p class="mt-2">Pour gérer vos cookies, consultez l'aide de votre navigateur :</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>Chrome : chrome://settings/cookies</li>
                            <li>Firefox : about:preferences#privacy</li>
                            <li>Safari : Préférences > Confidentialité</li>
                            <li>Edge : edge://settings/privacy</li>
                        </ul>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('5. Impact du Refus des Cookies') }}</h3>
                        <p>Le refus des cookies peut :</p>
                        <ul class="list-disc pl-6 mt-2 space-y-1">
                            <li>Limiter certaines fonctionnalités du site</li>
                            <li>Empêcher la mémorisation de vos préférences</li>
                            <li>Affecter l'expérience utilisateur optimale</li>
                        </ul>
                        <p>Les cookies essentiels ne peuvent pas être désactivés car ils sont nécessaires au fonctionnement du site.</p>
                    </div>

                    <div class="space-y-2">
                        <h3 class="text-lg font-semibold text-white">{{ __('6. Mise à Jour') }}</h3>
                        <p>La présente politique des cookies peut être mise à jour à tout moment. Nous vous encourageons à la consulter régulièrement.</p>
                        <p>Dernière mise à jour : {{ date('d/m/Y') }}</p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Particles.js -->
    <script src="https://cdn.jsdelivr.net/particles.js/2.0.0/particles.min.js"></script>
    <script>
        particlesJS('particles-js', {
            particles: {
                number: {
                    value: 80,
                    density: {
                        enable: true,
                        value_area: 800
                    }
                },
                color: {
                    value: ['#4F9BFF', '#2E86FF', '#0066FF', '#0052CC']
                },
                shape: {
                    type: 'circle'
                },
                opacity: {
                    value: 0.15,
                    random: true,
                    anim: {
                        enable: true,
                        speed: 1,
                        opacity_min: 0.05,
                        sync: false
                    }
                },
                size: {
                    value: 3,
                    random: true,
                    anim: {
                        enable: false
                    }
                },
                line_linked: {
                    enable: true,
                    distance: 150,
                    color: '#4F9BFF',
                    opacity: 0.15,
                    width: 1
                },
                move: {
                    enable: true,
                    speed: 2,
                    direction: 'none',
                    random: false,
                    straight: false,
                    out_mode: 'out',
                    bounce: false,
                    attract: {
                        enable: false,
                        rotateX: 600,
                        rotateY: 1200
                    }
                }
            },
            interactivity: {
                detect_on: 'window',
                events: {
                    onhover: {
                        enable: true,
                        mode: 'grab'
                    },
                    onclick: {
                        enable: true,
                        mode: 'push'
                    },
                    resize: true
                },
                modes: {
                    grab: {
                        distance: 140,
                        line_linked: {
                            opacity: 0.4
                        }
                    },
                    push: {
                        particles_nb: 4
                    }
                }
            },
            retina_detect: true
        });
    </script>

    @livewireScripts
    @stack('scripts')
</body>
</html>