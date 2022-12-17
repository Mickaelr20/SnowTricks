<?php

namespace App\DataFixtures;

use App\Entity\TrickCategory;
use Doctrine\Bundle\FixturesBundle\Fixture;
use Doctrine\Persistence\ObjectManager;
use Faker\Factory;
use Faker\Generator;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;

class AppFixtures extends Fixture
{
	private Generator $faker;
	public const DEFAULT_USER_USERNAME = 'Mickaelr20';
	public const DEFAULT_USER_EMAIL = 'mickaelr20@gmail.com';
	public const DEFAULT_USER_PASSWORD = 'azerty';

	public function __construct(private UserPasswordHasherInterface $passwordHasher)
	{
		$this->faker = Factory::create('fr_FR');
	}

	public function load(ObjectManager $manager): void
	{
		// Création des catégories de tricks : TrickCategory
		$trickCategoriesValues = [
			['name' => 'Grab'],
			['name' => 'Rotation'],
			['name' => 'Flip'],
			['name' => 'Rotation désaxée'],
			['name' => 'Slide'],
			['name' => 'One trick foot'],
			['name' => 'Old school'],
			['name' => 'Saut'],
		];
		$trickCategories = $this->createEntitiesAndPersist('TrickCategory', $trickCategoriesValues, $manager, 'name');
		$manager->flush();

		// Création d'utilisateurs
		$usersValues = [
			[
				'username' => self::DEFAULT_USER_USERNAME,
				'email' => self::DEFAULT_USER_EMAIL,
				'password' => self::DEFAULT_USER_PASSWORD,
				'profilePictureFilename' => 'pp-1.jpg',
			],
			[
				'username' => 'IDoGrabs',
				'email' => 'idograbs@example.com',
				'password' => 'IDoGrabs',
				'profilePictureFilename' => 'pp-2.jpg',
			], [
				'username' => 'Yalpaka',
				'email' => 'Yalpaka@example.com',
				'password' => 'Yalpaka',
				'profilePictureFilename' => 'pp-3.webp',
			], [
				'username' => 'Snowy78',
				'email' => 'Snowy78@example.com',
				'password' => 'Snowy78',
				'profilePictureFilename' => 'pp-4.jpg',
			], [
				'username' => 'xXBigBossXx',
				'email' => 'xXBigBossXx@example.com',
				'password' => 'xXBigBossXx',
				'profilePictureFilename' => 'pp-5.png',
			], [
				'username' => 'SuperDuperSnower',
				'email' => 'SuperDuperSnower@example.com',
				'password' => 'SuperDuperSnower',
				'profilePictureFilename' => 'pp-6.jpg',
			],
		];

		$users = $this->createEntitiesAndPersist('User', $usersValues, $manager, 'username');
		$manager->flush();

		// Création des tricks
		$tricksValues = [
			[ // Ollie
				'name' => 'Ollie',
				'description' => "Un Ollie est probablement le premier trick de snowboard que vous apprendrez.
                C'est votre introduction aux sauts de snowboard.
                Pour effectuer un Ollie, vous devez déplacer votre poids corporel vers votre jambe arrière.
                Sautez en vous assurant de mener avec votre jambe avant.
                Soulevez votre jambe arrière en ligne avec votre avant.
                Plus vous pratiquez le Ollie, plus vous pouvez sauter haut et plus vous pouvez amener vos pieds parallèlement.",
				'created' => $this->faker->dateTimeBetween('-8 months', '-7 months'),
				'slug' => 'ollie',
				'thumbnailFilename' => 'ollie.jpg',
				'videos' => [
					[
						'title' => 'Un Ollie par MaverixSnowLtd',
						'link' => 'https://www.youtube.com/watch?v=pt-J-gEtIlI&ab_channel=MaverixSnowLtd',
					], [
						'title' => 'Ollie, Pratiks',
						'link' => 'https://www.youtube.com/watch?v=BDpxekjUCqw&ab_channel=Pratiks',
					],
				],
				'images' => [
					[
						'name' => 'Faire un Ollie',
						'filename' => 'ollie.jpg',
					],
				],
				'comments' => [
					[
						'content' => 'Merci bien.',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['Yalpaka'],
					], [
						'content' => 'Un ollie quoi ... c\'est vrai qu\'il en faut pour tout le monde.',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['xXBigBossXx'],
					], [
						'content' => $this->faker->text(),
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['Yalpaka'],
					], [
						'content' => 'Un bon endroit où apprendre, bonne chance à tous !',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users[self::DEFAULT_USER_USERNAME],
					], [
						'content' => 'Enfin un endroit où apprendre, un grand merci à vous !',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['SuperDuperSnower'],
					],
				],
				'category' => $trickCategories['Saut'],
			],
			[ // Mute
				'name' => 'Mute',
				'description' => 'saisie de la carre frontside de la planche entre les deux pieds avec la main avant',
				'created' => $this->faker->dateTimeBetween('-8 months', '-7 months'),
				'slug' => 'grab-mute',
				'thumbnailFilename' => 'grab_mute.jpg',
				'videos' => [
					[
						'title' => 'Un Mute',
						'link' => 'https://www.youtube.com/watch?v=jm19nEvmZgM&ab_channel=MaverixSnowLtd',
					],
				],
				'images' => [
					[
						'name' => 'Faire un Mute',
						'filename' => 'grab_mute.jpg',
					],
				],
				'comments' => [
					[
						'content' => 'Géniale, merci j\'ai pu apprendre grâce à vous.',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['IDoGrabs'],
					], [
						'content' => 'Trop facile :)',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['Snowy78'],
					], [
						'content' => 'Super cool',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['Yalpaka'],
					],
				],
				'category' => $trickCategories['Grab'],
			],
			[ // Nollie
				'name' => 'Nollie',
				'description' => "Le Nollie est fondamentalement l'opposé d'un Ollie.
                Lorsque vous sautez, menez avec votre jambe arrière.
                Ensuite, soulevez votre jambe avant pour aligner vos pieds l'un avec l'autre.
                Vous constaterez probablement que vous pouvez rattraper quelques centimètres après seulement quelques essais.",
				'created' => $this->faker->dateTimeBetween('-8 months', '-7 months'),
				'slug' => 'nollie',
				'thumbnailFilename' => 'nollie.jpg',
				'videos' => [
					[
						'title' => 'Un Nollie',
						'link' => 'https://www.youtube.com/watch?v=apn53o796Tk&ab_channel=NomadSnowboard',
					],
				],
				'images' => [
					[
						'name' => 'Faire un Nollie',
						'filename' => 'nollie.jpg',
					],
					[
						'name' => 'Un Nollie',
						'filename' => 'nollie_2.jpg',
					],
				],
				'comments' => [
					[
						'content' => 'Excelent, j\'ai compris direct !',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['xXBigBossXx'],
					], [
						'content' => 'Niceuuuuuuuuuuu',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['Snowy78'],
					], [
						'content' => $this->faker->text(),
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['IDoGrabs'],
					],
				],
				'category' => $trickCategories['Saut'],
			],
			[ // Melon
				'name' => 'Melon',
				'description' => "Lorsque vous attrapez un peu d'air en snowboard, baissez les bras et attrapez le côté talon de la planche entre vos pieds.
                Félicitations, vous avez fait votre premier melon !",
				'created' => $this->faker->dateTimeBetween('-8 months', '-7 months'),
				'slug' => 'melon',
				'thumbnailFilename' => 'melon.jpg',
				'videos' => [
					[
						'title' => 'Un Melon par WixxTV',
						'link' => 'https://www.youtube.com/watch?v=FjxRYEMHyLw&ab_channel=WixxTV',
					], [
						'title' => 'Un Melon par SnowboardProCamp',
						'link' => 'https://www.youtube.com/watch?v=CA5bURVJ5zk&ab_channel=SnowboardProCamp',
					],
				],
				'images' => [
					[
						'name' => 'Faire un Melon',
						'filename' => 'melon.jpg',
					],
					[
						'name' => 'Melon (big air)',
						'filename' => 'melon_2.jpg',
					],
				],
				'comments' => [
					[
						'content' => 'La base mais quand même impresionnant !',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['IDoGrabs'],
					], [
						'content' => 'Niceuuuuuuuuuuu',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['Snowy78'],
					], [
						'content' => 'Une fois de plus, une nouvelle figure, j\'adore :)',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['SuperDuperSnower'],
					],
				],
				'category' => $trickCategories['Grab'],
			],
			[ // Indy
				'name' => 'Indy',
				'description' => "Vous pouvez effectuer un Indy en faisant un Ollie à partir d'un saut et en vous penchant pour saisir le bord des orteils de votre planche.
                Lâchez prise et repositionnez-vous pour un atterrissage en douceur.",
				'created' => $this->faker->dateTimeBetween('-8 months', '-7 months'),
				'slug' => 'indy',
				'thumbnailFilename' => 'indy.jpg',
				'videos' => [
					[
						'title' => 'Un Indy par SnowboardProCamp (regular)',
						'link' => 'https://www.youtube.com/watch?v=iKkhKekZNQ8&ab_channel=SnowboardProCamp',
					], [
						'title' => 'Un Indy par SnowboardProCamp',
						'link' => 'https://www.youtube.com/watch?v=6yA3XqjTh_w&ab_channel=SnowboardProCamp',
					],
				],
				'images' => [
					[
						'name' => 'Faire un indy',
						'filename' => 'indy.jpg',
					],
					[
						'name' => 'Un Indy 2',
						'filename' => 'indy_2.jpg',
					],
				],
				'comments' => [
					[
						'content' => 'Indy indeed !',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['SuperDuperSnower'],
					], [
						'content' => 'Niceuuuuuuuuuuu',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['Snowy78'],
					], [
						'content' => 'Classique mais efficace ! je suis fan :o',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users[self::DEFAULT_USER_USERNAME],
					], [
						'content' => $this->faker->text(),
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['Yalpaka'],
					],
				],
				'category' => $trickCategories['Grab'],
			],
			[ // Nose Grab
				'name' => 'Nose Grab',
				'description' => "Si vous pouvez faire un Melon et Indy, vous êtes prêt pour une prise de nez. En l'air, penchez-vous pour saisir l'avant de votre planche.
                Redressez votre corps et préparez-vous à un atterrissage facile.",
				'created' => $this->faker->dateTimeBetween('-8 months', '-7 months'),
				'slug' => 'nose-grab',
				'thumbnailFilename' => 'nose_grab.jpg',
				'videos' => [
					[
						'title' => 'Un Nose Grab par SnowboardProCamp',
						'link' => 'https://www.youtube.com/watch?v=CA5bURVJ5zk&ab_channel=SnowboardProCamp',
					], [
						'title' => 'Nose grab, SnowsurfMagazine',
						'link' => 'https://www.youtube.com/watch?v=L4bIunv8fHM&ab_channel=SnowsurfMagazine',
					],
				],
				'images' => [
					[
						'name' => 'Nose grab',
						'filename' => 'nose_grab.jpg',
					],
					[
						'name' => 'Un Nose Grab (fond noir)',
						'filename' => 'nose_grab_2.jpg',
					],
					[
						'name' => 'Nose grab',
						'filename' => 'nose_grab_3.jpg',
					],
				],
				'comments' => [
					[
						'content' => '"Nose grab" ça veut dire "saisir le nez" !',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['SuperDuperSnower'],
					], [
						'content' => 'Niceuuuuuuuuuuu',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['Snowy78'],
					], [
						'content' => $this->faker->text(),
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['xXBigBossXx'],
					], [
						'content' => 'Classique mais efficace ! encore de quoi frimer LOL',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['Yalpaka'],
					],
				],
				'category' => $trickCategories['Grab'],
			],
			[ // 50-50 (fifty-fifty)
				'name' => 'Fifty - Fifty (50-50)',
				'description' => "Le 50-50 vous initie aux slide tricks du snowboard.
                Lorsque vous vous approchez d'un rail ou d'une boîte, sautez pour atterrir dessus et montez dessus jusqu'à ce que vous vous détachiez à l'autre bout.
                Commencez par des rails courts jusqu'à ce que vous trouviez l'équilibre dont vous avez besoin pour rouler sur des rails plus longs.",
				'created' => $this->faker->dateTimeBetween('-8 months', '-7 months'),
				'slug' => 'fifty-fifty',
				'thumbnailFilename' => 'fifty_fifty.webp',
				'videos' => [
					[
						'title' => 'Comment slider en snowboard, NomadSnowboard',
						'link' => 'https://www.youtube.com/watch?v=n9ifYkF-ghw&ab_channel=NomadSnowboard',
					], [
						'title' => 'Fifty fifty, SnowboardAddiction',
						'link' => 'https://www.youtube.com/watch?v=e-7NgSu9SXg&ab_channel=SnowboardAddiction',
					],
				],
				'images' => [
					[
						'name' => 'Fifty - Fifty',
						'filename' => 'fifty_fifty.webp',
					],
					[
						'name' => 'Un 50-50',
						'filename' => 'fifty_fifty_2.webp',
					],
				],
				'comments' => [
					[
						'content' => '50% à droite et 50% à gauche ça fait un premier slide réussit à 100%',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users[self::DEFAULT_USER_USERNAME],
					], [
						'content' => 'Niceuuuuuuuuuuu',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['Snowy78'],
					], [
						'content' => $this->faker->text(),
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['xXBigBossXx'],
					], [
						'content' => 'Premier slide accompli !',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['Yalpaka'],
					],
				],
				'category' => $trickCategories['Slide'],
			],
			[ // Frontside 180
				'name' => 'Frontside 180',
				'description' => "Prêt à faire pivoter votre snowboard ? Avec un frontside 180, vous faites pivoter votre corps pour que vos talons mènent.
                Par exemple, si vous sautez en descendant avec votre pied gauche vers l'avant, vous effectuerez une rotation dans le sens inverse des aiguilles d'une montre pour un frontside 180.
                Arrêtez-vous lorsque votre jambe arrière devient votre jambe avant.",
				'created' => $this->faker->dateTimeBetween('-8 months', '-7 months'),
				'slug' => 'frontside-180',
				'thumbnailFilename' => 'frontside_180.jpg',
				'videos' => [
					[
						'title' => 'Un Frontside 180 par SnowboardAddiction',
						'link' => 'https://www.youtube.com/watch?v=-NuJCyR884I&ab_channel=SnowboardAddiction',
					], [
						'title' => 'SnowboardAddiction: Frontside 180',
						'link' => 'https://www.youtube.com/watch?v=GnYAlEt-s00&ab_channel=SnowboardAddiction',
					],
				],
				'images' => [
					[
						'name' => 'Frontside 180',
						'filename' => 'frontside_180.webp',
					],
					[
						'name' => 'Frontside 180',
						'filename' => 'frontside_180.jpg',
					],
				],
				'comments' => [
					[
						'content' => 'La base mais quand même impresionnant !',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['IDoGrabs'],
					], [
						'content' => '180 degrès, j\'ai la tête qui tourne, mais la planche aussi et ça dézingue !',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['SuperDuperSnower'],
					], [
						'content' => 'Sheeeeeeeeeeeeeesh',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['xXBigBossXx'],
					],
				],
				'category' => $trickCategories['Rotation'],
			],
			[ // Backflip
				'name' => 'Back flip',
				'description' => "Faites attention lorsque vous essayez un saut périlleux arrière. Vous aurez besoin de beaucoup de temps et d'espace pour effectuer le retournement avant d'atterrir.",
				'created' => $this->faker->dateTimeBetween('-8 months', '-7 months'),
				'slug' => 'backflip',
				'thumbnailFilename' => 'backflip.jpg',
				'videos' => [
					[
						'title' => 'Un Backflip par MalcolmMoore',
						'link' => 'https://www.youtube.com/watch?v=_8TBfD5VPnM&ab_channel=MalcolmMoore',
					], [
						'title' => 'SnowsurfMagazine: Backflip',
						'link' => 'https://www.youtube.com/watch?v=SlhGVnFPTDE&ab_channel=SnowsurfMagazine',
					],
				],
				'images' => [
					[
						'name' => 'Backflip',
						'filename' => 'backflip.jpg',
					],
					[
						'name' => 'Un Backflip',
						'filename' => 'backflip_2.jpg',
					],
				],
				'comments' => [
					[
						'content' => 'Impossible ça me fait trop peur :0',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['IDoGrabs'],
					], [
						'content' => '360° a la vertical j\'en ai les méninges complétement retournés !',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['SuperDuperSnower'],
					],
				],
				'category' => $trickCategories['Flip'],
			],
			[ // Front roll
				'name' => 'Front roll',
				'description' => "Le front roll déplace votre corps dans un mouvement vers l'avant, mais il s'incline un peu sur le côté.
                Maîtriser avant de passer à un flip avant complet.",
				'created' => $this->faker->dateTimeBetween('-8 months', '-7 months'),
				'slug' => 'front-roll',
				'thumbnailFilename' => 'front_roll.jpg',
				'videos' => [
					[
						'title' => 'Un Front roll par SnowboardProCamp',
						'link' => 'https://www.youtube.com/watch?v=eGJ8keB1-JM&ab_channel=SnowboardProCamp',
					], [
						'title' => 'Nicmorency: Front roll',
						'link' => 'https://www.youtube.com/watch?v=iTqv7rtmThM&ab_channel=Nicmorency',
					],
				],
				'images' => [
					[
						'name' => 'Front roll',
						'filename' => 'front_roll.jpg',
					],
					[
						'name' => 'Un Front roll',
						'filename' => 'front_roll_2.jpg',
					],
				],
				'comments' => [
					[
						'content' => 'Merci beaucoup !',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['Yalpaka'],
					], [
						'content' => 'Toujours plus de figure ! Yay',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['xXBigBossXx'],
					], [
						'content' => $this->faker->text(),
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['Yalpaka'],
					], [
						'content' => 'Un bon endroit où apprendre, bonne chance à tous !',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users[self::DEFAULT_USER_USERNAME],
					], [
						'content' => 'J\'adore cette figure :o',
						'created' => $this->faker->dateTimeBetween('-6 months'),
						'author' => $users['SuperDuperSnower'],
					],
				],
				'category' => $trickCategories['Flip'],
			],
		];

		$this->createEntitiesAndPersist('Trick', $tricksValues, $manager);
		$manager->flush();
	}

	/**
	 * Créer les entités à partir d'une liste associative.
	 *
	 * @return Collection<int, Entity>
	 */
	private function createEntitiesAndPersist(string $entityName, array $entitiesValues, ObjectManager $objectManager, string $resultKey = null): array
	{
		$results = [];
		foreach ($entitiesValues as $entityFields) {
			$className = 'App\Entity\\'.$entityName;
			$item = new $className();
			foreach ($entityFields as $fieldName => $fieldValue) {
				// Si la valeur est un array, créer le sous - item (Videos / Images ...)
				if ('array' === gettype($fieldValue)) {
					$subEntityName = substr(ucfirst($fieldName), 0, -1);
					$action = 'add'.$subEntityName;
					$subItems = $this->createEntitiesAndPersist($subEntityName, $fieldValue, $objectManager);
					foreach ($subItems as $subItem) {
						$item->$action($subItem);
					}
				} else {
					$action = 'set'.ucfirst($fieldName);
					if ('password' === $fieldName) {
						$item->$action($this->passwordHasher->hashPassword($item, $fieldValue));
					} else {
						$item->$action($fieldValue);
					}
				}
			}
			$objectManager->persist($item);
			if (!empty($resultKey)) {
				$key = 'get'.ucfirst($resultKey);
				$results[$item->$key()] = $item;
			} else {
				$results[] = $item;
			}
		}

		return $results;
	}
}
